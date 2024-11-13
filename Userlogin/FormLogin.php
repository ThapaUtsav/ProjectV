<?php
$host = 'localhost';
$dbname = 'arthasanjal';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the login credentials from the form
    $phoneNumber = $_POST['login-Phonenumber'];
    $inputPassword = $_POST['login-password'];
    $phoneNumber = $conn->real_escape_string($phoneNumber);
    $inputPassword = $conn->real_escape_string($inputPassword);

    $sql_headuser = "SELECT ID, PassW FROM headuser WHERE PH_num = '$phoneNumber' LIMIT 1";
    $result_headuser = $conn->query($sql_headuser);
//Headuser
    if ($result_headuser->num_rows > 0) {
        $user = $result_headuser->fetch_assoc();
        if (password_verify($inputPassword, $user['PassW'])) {
            session_start();
            $_SESSION['userID'] = $user['ID'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errorMessage = "Incorrect password. Please try again.";
        }

        //headuser check then low user check
    } else {
        $sql_lowuser = "SELECT L_U_id, PassW_1 FROM lowuserpassw WHERE L_U_id = 
            (SELECT L_U_id FROM lowuser WHERE Ph_1 = '$phoneNumber' 
            OR Ph_2 = '$phoneNumber' OR Ph_3 = '$phoneNumber' 
            OR Ph_4 = '$phoneNumber' OR Ph_5 = '$phoneNumber') LIMIT 1";

        $result_lowuser = $conn->query($sql_lowuser);
//users
        if ($result_lowuser->num_rows > 0) {
            $lowuser = $result_lowuser->fetch_assoc();
            if (password_verify($inputPassword, $lowuser['PassW_1'])) {
                session_start();
                $_SESSION['userID'] = $lowuser['L_U_id'];
                header("Location: dashboard.php");
                exit;
            } else {
                $errorMessage = "Incorrect password for lowuser. Please try again.";
            }
        } else {
            $errorMessage = "No user found with this phone number.";
        }
    }
}

$conn->close();
?>
