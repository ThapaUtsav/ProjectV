<?php

$host = 'localhost';
$dbname = 'arthasanjal';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Group-name']) && isset($_POST['signup-email']) && isset($_POST['signup-Phonenumber']) && isset($_POST['signup-password'])) {
        $grp = $_POST['Group-name'];
        $email = $_POST['signup-email'];
        $phone = $_POST['signup-Phonenumber'];
        $password = $_POST['signup-password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
            exit;
        }
        if (!preg_match('/(\+977)?[9][6-9]\d{8}$/', $phone)) {
            echo "Phone number must be exactly 10 digits.";
            exit;
        }

        $checkSql = "SELECT * FROM admin WHERE Email = ? OR Ph_num = ?";
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
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insertSql = "INSERT INTO headuser (Grp_n, Email, Ph_num, PassW) VALUES (?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ssss", $grp, $email, $phone, $hashed_password);
            if ($insertStmt->execute()) {
                header('Location: ../userlogin.html');
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
