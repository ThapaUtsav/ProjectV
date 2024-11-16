<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arthasanjal";

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from session
$userID = $_SESSION['userID'];

// Prepare and execute SQL query to fetch user data
$sql = "SELECT * FROM users WHERE account_num = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Check if user data was found
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user data
} else {
    echo "User not found. Please log in again.";
    exit();
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Page</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body class="light-mode">
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <a href="memberpage.php">Home</a>
        <a href="profile.php">My Profile</a>
        <a href="javascript:void(0);" onclick="toggleSubmenu('account-information')">Account Information</a>
        <div class="submenu" id="account-information">
            <a href="savingaccinfo.php">Saving Account Information</a>
            <a href="loanaccinfo.php">Loan Account Information</a>
        </div>
        <a href="javascript:void(0);" onclick="toggleSubmenu('services')">Services</a>
        <div class="submenu" id="services">
            <a href="requestloan.php">Request Loan</a>
            <a href="reqaccstatement.php">Request Account Statement</a>
        </div>
        <a href="support.php">Support/Help</a>
        <a href="#">Sign Out</a>
    </div>

    <!-- Header Section -->
    <header>
        <div class="hamburger-menu" onclick="toggleSidebar()">
            &#9776;
        </div>
        <div class="nav-links">
            <a href="notifications.php">Notifications</a>
            <a href="javascript:void(0);" onclick="toggleThemeDropdown()">Theme</a>
            <div class="theme-dropdown" id="theme-dropdown">
                <a href="#" onclick="switchMode('light')">Light Mode</a>
                <a href="#" onclick="switchMode('dark')">Dark Mode</a>
            </div>
        </div>
    </header>

    <!-- Profile Page Content -->
    <div class="profile-container">
        <h2>Your Profile</h2>
        <!-- Display user information -->
        <div class="profile-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['street']) . ', ' . htmlspecialchars($user['city']) . ', ' . htmlspecialchars($user['state']); ?></p>
            <p><strong>Account Created On:</strong> <?php echo htmlspecialchars($user['acc_date']); ?></p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date("Y"); ?> Artha Sanjal. All rights reserved.
    </footer>

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Toggle Submenu
        function toggleSubmenu(id) {
            document.getElementById(id).classList.toggle('active');
        }

        // Theme switching logic
        function toggleThemeDropdown() {
            document.getElementById('theme-dropdown').classList.toggle('show');
        }

        function switchMode(mode) {
            if (mode === 'dark') {
                document.body.classList.add('dark-mode');
                document.body.classList.remove('light-mode');
            } else {
                document.body.classList.add('light-mode');
                document.body.classList.remove('dark-mode');
            }
        }
    </script>
</body>
</html>
