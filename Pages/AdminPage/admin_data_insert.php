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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = mysqli_real_escape_string($conn, $_POST['admin_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);  

    $sql = "INSERT INTO admins_info (admin_name, username, dob, street, city, state, account_number) 
            VALUES ('$admin_name', '$username', '$dob', '$street', '$city', '$state', '$account_number')";


    if ($conn->query($sql) === TRUE) {
        header("Location:../Userlogin/userlogin.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
