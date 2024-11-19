<?php
session_start();

// Check if the user is logged in and session variable 'userID' is set
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$admin_account_number = $_SESSION['userID']; // Get the admin account number from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';  // Get the username from the session

// Database connection
$servername = "localhost";
$dbusername = "root";  // Assuming default username
$password = "";
$dbname = "arthasanjal";

// Create connection
$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$loan_date = $_POST['loan_date'];
$loan_amount = $_POST['loan_amount'];
$interest_rate = $_POST['interest_rate'];
$repayment_period = $_POST['repayment_period'];
$payment_method = $_POST['payment_method'];
$total_repayment = $_POST['total_repayment'];
$monthly_repayment = $_POST['monthly_repayment'];
$account_number = $_POST['account_number'];
$username = $_POST['username'];

// Sanitize input to avoid SQL injection
$loan_date = $conn->real_escape_string($loan_date);
$loan_amount = $conn->real_escape_string($loan_amount);
$interest_rate = $conn->real_escape_string($interest_rate);
$repayment_period = $conn->real_escape_string($repayment_period);
$payment_method = $conn->real_escape_string($payment_method);
$total_repayment = $conn->real_escape_string($total_repayment);
$monthly_repayment = $conn->real_escape_string($monthly_repayment);
$account_number = $conn->real_escape_string($account_number);
$username = $conn->real_escape_string($username);

// Check if the account_number exists in the users table
$sql_check_user = "SELECT * FROM users WHERE account_num = '$account_number'";
$result_check_user = $conn->query($sql_check_user);

if ($result_check_user->num_rows == 0) {
    // If the account number doesn't exist in users table, show error and exit
    $_SESSION['deposit_message'] = "Error: The account number does not exist.";
    header("Location: index.php"); // Redirect back to the loan deposit page
    exit();
}

// Insert loan details into the loans table, including outstanding_balance
$sql = "INSERT INTO loans (account_number, loan_date, loan_amount, interest_rate, repayment_period, total_repayment, monthly_repayment, status, payment_method, created_by, outstanding_balance)
        VALUES ('$account_number', '$loan_date', '$loan_amount', '$interest_rate', '$repayment_period', '$total_repayment', '$monthly_repayment', 'Pending', '$payment_method', '$username', '$loan_amount')"; // Set outstanding_balance = loan_amount

if ($conn->query($sql) === TRUE) {
    // Set success message in the session
    $_SESSION['deposit_message'] = "Loan request submitted successfully!";
    header("Location: loanindex.php"); // Redirect back to the loan deposit page
    exit();
} else {
    // Set error message in the session
    $_SESSION['deposit_message'] = "Error: " . $conn->error;
    header("Location: loanindex.php"); // Redirect back to the loan deposit page
    exit();
}

// Close connection
$conn->close();
?>
