<?php
session_start();

if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$admin_account_number = $_SESSION['userID']; 
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';  

$servername = "localhost";
$dbusername = "root";  
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today_date = date('Y-m-d');

if (isset($_SESSION['deposit_message'])) {
    echo "<p style='color: green;'>".$_SESSION['deposit_message']."</p>";
    unset($_SESSION['deposit_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Table</title>
  <link rel="stylesheet" href="usstyle.css">
  <script src="userfscript.js" defer></script>
</head>
<body class="light-mode">
<div class="sidebar" id="sidebar">
        <a href="../Pages/MemberPage/memberpage.html">Home</a>
        <a href="../Pages/MemberPage/profile.php">My Profile</a>
        <a href="#" onclick="toggleSubmenu('accountManagementSubmenu')">Account Information</a>
        <div class="submenu" id="accountManagementSubmenu">
            <a href="index.php">Deposit</a>
            <a href="loanindex.php">Loan</a>
        </div>
        <a href="#" onclick="toggleSubmenu('reportsSubmenu')">Services</a>
        <div class="submenu" id="reportsSubmenu">
        <a href="../Pages/AdminPage/reqaccstatement.php">Loan Repayments</a>
            <a href="../Pages/MemberPage/DeptHist.php">Deposit History</a>
        </div>
        <a href="../Pages/MemberPage/help.php">Support/Help</a>
        <a href="../Pages/MemberPage/signout.php">Sign Out</a>
    </div>

    <header>
        <div class="hamburger-menu" onclick="toggleSidebar()">
            &#9776;
        </div>
        <div class="nav-links">
            <a href="../Pages/MemberPage/notification.php">Notifications</a>
            <div class="theme-link" onclick="toggleThemeDropdown()">Theme</div>
            <div class="theme-dropdown" id="theme-dropdown">
                <a href="#" onclick="switchMode('light')">Light Mode</a>
                <a href="#" onclick="switchMode('dark')">Dark Mode</a>
            </div>
        </div>
    </header>

    <h1>Deposit</h1>
    <div class="subpage">
    <form id="paymentForm" action="submit.php" method="POST">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($today_date); ?>" required>
        
        <label for="payment">Payment Amount:</label>
        <input type="number" id="payment" name="payment_amount" required>
        
        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="mobile">Digital Wallet</option>
            <option value="banking">Mobile Banking</option>
        </select>
        
        <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($admin_account_number); ?>">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>"> 
        
        <button type="submit">Submit</button>
    </form>

    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
        }
    ?>
    </div>

</body>
</html>
