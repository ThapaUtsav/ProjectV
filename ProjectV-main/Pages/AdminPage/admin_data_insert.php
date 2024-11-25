<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['acc_no'])) {
    header("Location: ../Userlogin/userlogin.html");
    exit();
}

$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "arthasanjal";  

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $admin_name = trim($_POST['admin_name']);
    $username = trim($_POST['username']);
    $dob = $_POST['dob'];
    $street = trim($_POST['street']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $account_number = $_POST['account_number'];

    // Check if required fields are empty
    if (empty($admin_name) || empty($username) || empty($dob) || empty($street) || empty($city) || empty($state)) {
        echo "All fields are required.";
        exit();
    }

    // Prepare SQL query with placeholders to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO admins_info (admin_name, username, dob, street, city, state, account_number) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("sssssss", $admin_name, $username, $dob, $street, $city, $state, $account_number);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the login page after successful insertion
        header("Location: ../../Userlogin/userlogin.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
}
?>
