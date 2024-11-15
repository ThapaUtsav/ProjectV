<?php
// Database credentials
$host = 'localhost';
$dbname = 'arthasanjal';
$username = 'root';
$password = '';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize user inputs
    $phoneNumber = $_POST['login-Phonenumber'];
    $inputPassword = $_POST['login-password'];

    // Escape user inputs for security
    $phoneNumber = $conn->real_escape_string($phoneNumber);
    $inputPassword = $conn->real_escape_string($inputPassword);

    // First, check if the phone number exists in the admin table
    $sql_admin = "SELECT account_number, PassW FROM admin WHERE PH_num = ? LIMIT 1";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("s", $phoneNumber);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    if ($result_admin->num_rows > 0) {
        $admin = $result_admin->fetch_assoc();
        if (password_verify($inputPassword, $admin['PassW'])) {
            session_start();
            session_regenerate_id(true); 
            $_SESSION['userID'] = $admin['account_number'];  
            $_SESSION['role'] = 'admin';

            header("Location: ../Pages/AdminPage/admin_page.html");
            exit;
        } else {
            $errorMessage = "Incorrect password for admin. Please try again.";
        }
    } else {
        // If admin login fails, check the users table
        $sql_user = "SELECT account_num, password FROM users WHERE phone = ? LIMIT 1";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("s", $phoneNumber);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($result_user->num_rows > 0) {
            // User found, check the password
            $user = $result_user->fetch_assoc();
            if (password_verify($inputPassword, $user['password'])) {
                // Start session securely for user
                session_start();
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['userID'] = $user['account_num'];  // Store user account number
                $_SESSION['role'] = 'user'; // Role for the user (user)

                // Redirect to the user dashboard
                header("Location: ../Pages/MemberPage/memberpage.php");
                exit;
            } else {
                $errorMessage = "Incorrect password for user. Please try again.";
            }
        } else {
            $errorMessage = "No user found with this phone number.";
        }
    }

    // Close prepared statements
    $stmt_admin->close();
    $stmt_user->close();
}

// Close the database connection
$conn->close();
?>