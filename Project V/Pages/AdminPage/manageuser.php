<?php
// Database connection
$servername = "localhost";
$username = "root"; // adjust as needed
$password = ""; // adjust as needed
$dbname = "arthasanjal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Securely hash password
$email = $_POST['email'];
$dob = $_POST['dob'];
$phone = $_POST['phone'];
$street = $_POST['street'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$accountType = $_POST['accountType'];
$accDate = $_POST['accDate'];

// Insert data into database
$sql = "INSERT INTO user_profiles (name, username, password, email, dob, phone, street, city, state, zip, accountType, accDate)
VALUES ('$name', '$username', '$password', '$email', '$dob', '$phone', '$street', '$city', '$state', '$zip', '$accountType', '$accDate')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully. Redirecting to user information page....";
    echo '<meta http-equiv="refresh" content="3;url=userinfo.php">';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
