<?php
session_start();

// Check if the user is logged in and session variable 'userID' is set
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$admin_account_number = $_SESSION['userID']; // Get the admin account number from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';  // Get the username from the session

// Database connection
$servername = "localhost";
$dbusername = "root";  // Assuming default username
$password = "";
$dbname = "arthasanjal";

// Create connection
$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data from POST request
$date = $_POST['date'];
$payment_amount = $_POST['payment_amount'];
$payment_method = $_POST['payment_method'];
$account_number = $_POST['account_number'];  // From hidden input field in the form

// Set the remarks field to "approved" by default
$remarks = "approved";

// Prepare an SQL query to insert the data into the payments table
$sql = "INSERT INTO payments (account_number, date, payment_amount, remarks, payment_method, username)
        VALUES (?, ?, ?, ?, ?, ?)";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters to the statement (s for string, d for decimal)
$stmt->bind_param("sssdss", $account_number, $date, $payment_amount, $remarks, $payment_method, $username);

// Execute the query
if ($stmt->execute()) {
    // Set a success message in the session
    $_SESSION['deposit_message'] = "Deposit successful!";  
    header('Location: index.php'); // Redirect after successful insert
    exit();
} else {
    echo "Error inserting record: " . $stmt->error;
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>
