<?php

$host = 'localhost';
$dbname = 'arthasanjal';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Field set exist or not

    if (isset($_POST['signup-email']) && isset($_POST['signup-Phonenumber']) && isset($_POST['signup-password'])) {
        $email = $_POST['signup-email'];
        $phone = $_POST['signup-Phonenumber'];
        $password = $_POST['signup-password'];

        // Validate email //W3 school
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
            exit;
        }
        //Regex for phone number verification
        if (!preg_match('/(\+977)?[9][6-9]\d{8}$/', $phone)) {
            echo "Phone number must be exactly 10 digits.";
            exit;
        }

        // Previous signupsa
        $checkSql = "SELECT * FROM headuser WHERE Email = ? OR Ph_num = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script type='text/javascript'>
            alert('User Already Exist!');
            window.location.href = '../Userlogin/userlogin.html';
            </script>";
        } else {
            // Hashing the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insertSql = "INSERT INTO headuser (Email, Ph_num, PassW) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);

            $insertStmt->bind_param("sss", $email, $phone, $hashed_password);

            if ($insertStmt->execute()) {
                header('Location:../FrontPage/Signedup.html');
                exit();
            } else {
                echo "Error: " . $insertStmt->error;
            }

            $insertStmt->close();
        }

        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();
?>
