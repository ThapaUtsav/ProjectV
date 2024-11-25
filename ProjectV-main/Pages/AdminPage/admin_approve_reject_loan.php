<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

if (isset($_POST['loan_id']) && isset($_POST['status'])) {
    $loan_id = $_POST['loan_id'];
    $status = $_POST['status'];  // 'approved' or 'rejected'

    // Validate status input
    if ($status !== 'approved' && $status !== 'rejected') {
        die("Invalid status.");
    }

    $servername = "localhost";
    $dbusername = "root";  // Assuming default username
    $password = "";
    $dbname = "arthasanjal";

    // Create database connection
    $conn = new mysqli($servername, $dbusername, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check the current loan status to make sure we only process loans that are pending
    $sql_check = "SELECT status FROM loans WHERE loan_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    if (!$stmt_check) {
        die("Failed to prepare check query: " . $conn->error);
    }
    $stmt_check->bind_param("i", $loan_id);
    $stmt_check->execute();
    $stmt_check->store_result();
    $stmt_check->bind_result($current_status);
    $stmt_check->fetch();

    // Only process loans that are pending
    if ($current_status === 'approved' || $current_status === 'rejected') {
        die("This loan has already been processed.");
    }

    // Update the status of the loan (either 'approved' or 'rejected')
    $sql_update = "UPDATE loans SET status = ? WHERE loan_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    if (!$stmt_update) {
        die("Failed to prepare update query: " . $conn->error);
    }

    $stmt_update->bind_param("si", $status, $loan_id);
    $stmt_update->execute();

    // Check if the update was successful
    if ($stmt_update->affected_rows > 0) {
        // Redirect back to the loan approvals page after success
        header("Location: admin_page.html");
        exit();
    } else {
        echo "Failed to update loan status. Please try again.";
    }

    $stmt_check->close();
    $stmt_update->close();
    $conn->close();
} else {
    echo "Invalid request. Loan ID and status are required.";
}
?>
