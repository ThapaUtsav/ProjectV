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

// Get today's date (in Y-m-d format)
$today_date = date('Y-m-d');

// Display the success message if set in the session
if (isset($_SESSION['deposit_message'])) {
    echo "<p style='color: green;'>".$_SESSION['deposit_message']."</p>"; // Success message
    unset($_SESSION['deposit_message']);  // Unset the message after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Table</title>
  <link rel="stylesheet" href="usstyle.css">
  <script src="script.js"></script>
</head>
<body class="light-mode">
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <!-- Home and Profile -->
        <a href="../Pages/MemberPage/memberpage.html">Home</a>
        <a href="../Pages/MemberPage/profile.php">My Profile</a>
    
        <!-- Account Information with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('account-information')">Account Information</a>
        <div class="submenu" id="account-information">
            <a href="../../userfinance/index.php">Deposit Amount</a>
            <a href="loanindex.php">Loan Amount</a>
        </div>
    
        <!-- Services with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('services')">Services</a>
        <div class="submenu" id="services">
            <a href="../Pages/MemberPage/reqaccstatement.php">Account Statement</a>
            <!-- Loan Repayment Link -->
            <a href="../Pages/MemberPage/reqaccstatement.php">Loan Repayment</a>
        </div>
    
        <!-- Support and Sign Out -->
        <a href="../Pages/MemberPage/support.php">Support/Help</a>
        <a href="../Pages/MemberPage/signout.php">Sign Out</a>
    </div>

    <!-- Header Section -->
    <header>
        <div class="hamburger-menu" onclick="toggleSidebar()">
            &#9776;
        </div>
        <div class="nav-links">
            <a href="#">Notifications</a>
            <div class="theme-link" onclick="toggleThemeDropdown()">
                Theme
            </div>
            <div class="theme-dropdown" id="theme-dropdown">
                <a href="#" onclick="switchMode('light')">Light Mode</a>
                <a href="#" onclick="switchMode('dark')">Dark Mode</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <h1>Deposit</h1>
    <form id="paymentForm" action="submit.php" method="POST">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($today_date); ?>" required>
        
        <label for="payment">Payment Amount:</label>
        <input type="number" id="payment" name="payment_amount" required>
        
        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="mobile">Mobile</option>
            <option value="banking">Banking</option>
        </select>
        
        <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($admin_account_number); ?>">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>"> <!-- Store username but don't display it -->
        
        <button type="submit">Submit</button>
    </form>

    <?php
        // Debugging: Check POST data when the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
        }
    ?>
</body>
</html>
