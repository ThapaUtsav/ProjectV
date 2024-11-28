<?php
session_start();

if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$admin_account_number = $_SESSION['userID'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$date = $_POST['date'];
$payment_amount = $_POST['payment_amount'];
$payment_method = $_POST['payment_method'];
$account_number = $_POST['account_number'];

$remarks = "approve";

$sql = "INSERT INTO payments (account_number, date, payment_amount, remarks, payment_method, username)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdss", $account_number, $date, $payment_amount, $remarks, $payment_method, $username);

if ($stmt->execute()) {
    $_SESSION['deposit_message'] = "Deposit successful!";
    header('Location: index.php');
    exit();
} else {
    echo "Error inserting record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
