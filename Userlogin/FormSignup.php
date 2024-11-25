<?php

$host = 'localhost';
$dbname = 'arthasanjal';
$username = 'root';
$password = '';

session_start();

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['Group-name']) && isset($_POST['signup-email']) && isset($_POST['signup-Phonenumber']) && isset($_POST['signup-password']) && isset($_POST['signup-account_number'])) {

        // Get form input values
        $grp = $_POST['Group-name'];
        $email = $_POST['signup-email'];
        $phone = $_POST['signup-Phonenumber'];
        $password = $_POST['signup-password'];
        $account_number = $_POST['signup-account_number'];
        $_SESSION['acc_no'] = $account_number;

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
            exit;
        }

        // Validate phone number (10 digits or valid international format)
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
            alert('User Already Exists!');
            window.location.href = '../Userlogin/userlogin.php';
            </script>";
            exit;
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $insertSql = "INSERT INTO admin (account_number, Grp_n, Email, Ph_num, PassW) VALUES (?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("sssss", $account_number, $grp, $email, $phone, $hashed_password);

            if ($insertStmt->execute()) {
                header('Location: ../Pages/AdminPage/admin_data.php');
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
