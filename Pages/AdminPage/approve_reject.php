<?php
session_start();


if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

if (isset($_POST['payment_id']) && isset($_POST['status'])) {
    $payment_id = $_POST['payment_id'];
    $status = $_POST['status']; 

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


    $sql = "UPDATE payments SET remarks = ? WHERE payment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $payment_id); 
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: admin_page.html");
        exit();
    } else {
        echo "Failed to update deposit status.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>
