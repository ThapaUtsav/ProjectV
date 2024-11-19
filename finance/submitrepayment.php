<?php
session_start();

if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$user_account_number = $_SESSION['userID'];
$loan_id = $_POST['loan_id'];  
$repayment_amount = floatval($_POST['repayment_amount']); 

$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($repayment_amount <= 0) {
    die("Invalid repayment amount. Please enter a valid positive amount.");
}

$sql = "SELECT loan_amount, outstanding_balance, status, repayment_status 
        FROM loans 
        WHERE loan_id = ? AND account_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $loan_id, $user_account_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $loan = $result->fetch_assoc();
    $current_outstanding_balance = $loan['outstanding_balance'];

    if ($repayment_amount > $current_outstanding_balance) {
        die("Repayment amount exceeds the outstanding balance.");
    }

    $new_outstanding_balance = $current_outstanding_balance - $repayment_amount;
    $loan_status = $loan['status'];
    $repayment_status = 'Partial';

    if ($new_outstanding_balance <= 0) {
        $new_outstanding_balance = 0;
        $loan_status = 'Paid';
        $repayment_status = 'Paid';
    }

    $update_sql = "UPDATE loans SET outstanding_balance = ?, status = ?, repayment_status = ? WHERE loan_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("dsdi", $new_outstanding_balance, $loan_status, $repayment_status, $loan_id);
    if ($update_stmt->execute()) {
        $repayment_date = date('Y-m-d');
        $insert_sql = "INSERT INTO repayments (loan_id, repayment_amount, repayment_date) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ids", $loan_id, $repayment_amount, $repayment_date);
        if ($insert_stmt->execute()) {
            echo "Repayment successfully recorded.";
        } else {
            echo "Error: Could not record the repayment.";
        }
        $insert_stmt->close();
    } else {
        echo "Error: Could not update loan details.";
    }

    $update_stmt->close();
} else {
    echo "Loan not found or does not belong to the user.";
}

$conn->close();
?>
