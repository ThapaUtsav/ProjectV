<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

// Check if payment_id and status are provided
if (isset($_POST['payment_id']) && isset($_POST['status'])) {
    $payment_id = $_POST['payment_id'];
    $status = $_POST['status']; // approved or rejected

    // Database connection
    $servername = "localhost";
    $dbusername = "root";
    $password = "";
    $dbname = "arthasanjal";

    $conn = new mysqli($servername, $dbusername, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the status in the payments table
    $sql = "UPDATE payments SET remarks = ? WHERE payment_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("si", $status, $payment_id);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Payment has been $status.";
    } else {
        $_SESSION['status_message'] = "Error updating payment status.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();

    // Redirect back to the pending approvals page
    header("Location: notification.php");
    exit();
} else {
    die("Invalid request. Missing payment ID or status.");
}
?>
