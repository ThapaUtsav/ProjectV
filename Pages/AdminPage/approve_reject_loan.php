<?php
session_start();

// Check if the user is logged in and session variable 'userID' is set
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

if (isset($_POST['loan_id']) && isset($_POST['status'])) {
    $loan_id = $_POST['loan_id'];
    $status = $_POST['status'];  // 'approved' or 'rejected'

    if ($status !== 'approved' && $status !== 'rejected') {
        die("Invalid status.");
    }

    // Database connection
    $servername = "localhost";
    $dbusername = "root";  
    $password = "";
    $dbname = "arthasanjal";

    $conn = new mysqli($servername, $dbusername, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query to update the loan status
    $sql = "UPDATE loans SET status = ? WHERE loan_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $loan_id); // 'si' means string and integer types
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect back to the admin page after updating
        header("Location: admin_page.html");
        exit();
    } else {
        echo "Failed to update loan status.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
