<?php
session_start();  // Start the session at the very beginning of the script

// Database credentials
$host = 'localhost';
$dbname = 'arthasanjal';
$username = 'root';
$password = '';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = "";  // Initialize error message

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
        // Check the password
        if (password_verify($inputPassword, $admin['PassW'])) {
            // Fetch the username from the admins_info table
            $sql_admin_info = "SELECT username FROM admins_info WHERE account_number = ? LIMIT 1";
            $stmt_admin_info = $conn->prepare($sql_admin_info);
            $stmt_admin_info->bind_param("s", $admin['account_number']);
            $stmt_admin_info->execute();
            $result_admin_info = $stmt_admin_info->get_result();

            if ($result_admin_info->num_rows > 0) {
                $admin_info = $result_admin_info->fetch_assoc();
                session_regenerate_id(true);  // Regenerate session ID to prevent session fixation
                $_SESSION['userID'] = $admin['account_number'];  
                $_SESSION['role'] = 'admin';
                $_SESSION['username'] = $admin_info['username'];  // Store the admin username in the session

                header("Location: ../Pages/AdminPage/admin_page.html");  // Redirect after successful login
                exit;
            } else {
                $errorMessage = "Admin username not found.";
            }

            // Close the statement for fetching admin username
            $stmt_admin_info->close();
        } else {
            $errorMessage = "Incorrect password for admin. Please try again.";
        }
    } else {
        // If admin login fails, check the users table
        $sql_user = "SELECT account_num, password, username FROM users WHERE phone = ? LIMIT 1";  // Added `username` to SELECT query
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("s", $phoneNumber);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($result_user->num_rows > 0) {
            // User found, check the password
            $user = $result_user->fetch_assoc();
            if (password_verify($inputPassword, $user['password'])) {
                session_regenerate_id(true);  // Regenerate session ID to prevent session fixation
                $_SESSION['userID'] = $user['account_num'];  // Store user account number
                $_SESSION['role'] = 'user';  // Role for the user
                $_SESSION['username'] = $user['username'];  // Store the username in the session

                header("Location: ../Pages/MemberPage/memberpage.html");  // Redirect to user dashboard
                exit;
            } else {
                $errorMessage = "Incorrect password for user. Please try again.";
            }
        } else {
            $errorMessage = "No user found with this phone number.";
        }

        // Close the statement for fetching user info
        $stmt_user->close();
    }

    // Close prepared statements for admin login check
    $stmt_admin->close();
}

// Close the database connection
$conn->close();

// Optionally, display error messages (if required)
if (!empty($errorMessage)) {
    echo "<p>Error: $errorMessage</p>";
}
?>
