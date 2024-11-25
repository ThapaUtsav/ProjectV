<?php
// Assuming you have received the payment_id and status from the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['payment_id'];
    $status = $_POST['status'];

    // Database connection
    $servername = "localhost";
    $dbusername = "root";
    $password = "";
    $dbname = "arthasanjal";
    $conn = new mysqli($servername, $dbusername, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the payment status
    $sql = "UPDATE payments SET status = ? WHERE payment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $payment_id);

    if ($stmt->execute()) {
        echo "Payment status updated successfully.";
    } else {
        echo "Error updating payment status.";
    }

    $stmt->close();
    $conn->close();
}
?>
