<?php
session_start();

if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

if (isset($_POST['loan_id']) && isset($_POST['status'])) {
    $loan_id = $_POST['loan_id'];
    $status = $_POST['status'];  // 'approved' or 'rejected'

    if ($status !== 'approved' && $status !== 'rejected') {
        die("Invalid status.");
    }

    $servername = "localhost";
    $dbusername = "root";
    $password = "";
    $dbname = "arthasanjal";

    $conn = new mysqli($servername, $dbusername, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update user status in the loan table
    $sql = "UPDATE loans SET user_status = ? WHERE loan_id = ? AND account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $loan_id, $_SESSION['userID']); // Only allow user to update their own loan
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: user_loans.php");
        exit();
    } else {
        echo "Failed to update loan status.";
    }

    $stmt->close();
    $conn->close();
}
?>
