<?php
session_start();



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arthasanjal";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Securely hash the password
$email = $_POST['email'];
$dob = $_POST['dob'];
$phone = $_POST['phone'];
$street = $_POST['street'];
$city = $_POST['city'];
$state = $_POST['state'];
$account_number = $_POST['accnumber']; // Account number from form
$accDate = $_POST['accDate'];

$sql = "INSERT INTO users (account_num, name, username, password, email, dob, phone, street, city, state, acc_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing the SQL statement: " . $conn->error);
}
$stmt->bind_param("sssssssssss", $account_number, $name, $username, $password, $email, $dob, $phone, $street, $city, $state, $accDate);
if ($stmt->execute()) {
    echo "New user created successfully. Redirecting...";
    echo '<meta http-equiv="refresh" content="3;url=userinfo.php">'; // Redirect to a page after 3 seconds
} else {
    echo "Error: " . $stmt->error; 
}

$stmt->close();
$conn->close();
?>
