<?php
session_start();

if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$admin_account_number = $_SESSION['userID'];

$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today_date = date('Y-m-d');
$current_month = date('m');
$current_year = date('Y');

$sql = "SELECT * FROM payments WHERE account_number = '$admin_account_number' AND MONTH(date) = '$current_month' AND YEAR(date) = '$current_year'";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_amount = $_POST['payment_amount'];
    $payment_method = $_POST['payment_method'];

    $sql = "INSERT INTO payments (account_number, payment_amount, payment_method, date) VALUES ('$admin_account_number', '$payment_amount', '$payment_method', '$today_date')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['deposit_message'] = "Deposit successful.";
    } else {
        $_SESSION['deposit_message'] = "Error: " . $conn->error;
    }

    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Table</title>
  <link rel="stylesheet" href="indexstyle.css">
  <script src="script.js"></script>
</head>
<body class="light-mode">
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <a href="../Pages/AdminPage/admin_page.html">Home</a>
        <a href="javascript:void(0);" onclick="toggleSubmenu('userManagementSubmenu')">User Management</a>
        <div class="submenu" id="userManagementSubmenu">
            <a href="../Pages/AdminPage/add_user.php">Add New User</a>
            <a href="../Pages/AdminPage/userinfo.php">Manage User Information</a>
        </div>
        <a href="#" onclick="toggleSubmenu('accountManagementSubmenu')">Account Management</a>
        <div class="submenu" id="accountManagementSubmenu">
            <a href="index.php">Deposit</a>
            <a href="loanindex.php">Loan</a>
        </div>
        <a href="javascript:void(0);" onclick="toggleSubmenu('loanRepaymentSubmenu')">Repayment</a>
        <div class="submenu" id="loanRepaymentSubmenu">
            <a href="../Pages/AdminPage/loan_repayment.php">Loan Repayments</a>
        </div>
        <a href="#" onclick="toggleSubmenu('reportsSubmenu')">Reports</a>
        <div class="submenu" id="reportsSubmenu">
            <a href="../Pages/AdminPage/monthlyreport.php">Monthly Reports</a>
            <a href="../Pages/AdminPage/annualreport.php">Annual Reports</a>
            <a href="loanreport.php">Loan Reports</a>
        </div>
        <a href="../Pages/AdminPage/help.php">Support/Help</a>
        <a href="../Pages/AdminPage/signout.php">Sign Out</a>
    </div>

    <!-- Header Section -->
    <header>
        <div class="hamburger-menu" onclick="toggleSidebar()">
            &#9776;
        </div>
        <div class="nav-links">
            <a href="../Pages/AdminPage/notification.php">Notifications</a>
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
    </div>
</body>
</html>
