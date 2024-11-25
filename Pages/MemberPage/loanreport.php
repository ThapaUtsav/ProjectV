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

$sql = "SELECT loan_id, loan_amount, interest_rate, repayment_period, total_repayment, monthly_repayment, loan_date, status, created_by FROM loans WHERE account_number = '$admin_account_number'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Report</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body class="light-mode">
<div class="sidebar" id="sidebar">
        <!-- Home and Profile -->
        <a href="memberpage.html">Home</a>
        <a href="profile.php">My Profile</a>
    
        <!-- Account Information with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('account-information')">Account Information</a>
        <div class="submenu" id="account-information">
            <a href="../../userfinance/index.php">Deposit </a>
            <a href="../../userfinance/loanindex.php">Loan </a>
        </div>
    
        <!-- Services with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('services')">Services</a>
        <div class="submenu" id="services">
            <a href="reqaccstatement.php">Loan Repayment</a>
            <a href="DepHist.php">Deposit History</a>
        </div>
    
        <!-- Support and Sign Out -->
        <a href="support.php">Support/Help</a>
        <a href="signout.php">Sign Out</a>
    </div>
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

    <div class="main-content">
        <h1>Loan Report</h1>
        <div class="loan-table-container">
            <h2>Current Loan Accounts</h2>
            <?php if ($result->num_rows > 0): ?>
                <table class="loan-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Loan Amount</th>
                            <th>Interest Rate (%)</th>
                            <th>Repayment Period (Months)</th>
                            <th>Total Repayment</th>
                            <th>Monthly Repayment</th>
                            <th>Status</th>
                            <th>Loan Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                <td><?php echo htmlspecialchars($row['loan_amount']); ?></td>
                                <td><?php echo htmlspecialchars($row['interest_rate']); ?></td>
                                <td><?php echo htmlspecialchars($row['repayment_period']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_repayment']); ?></td>
                                <td><?php echo htmlspecialchars($row['monthly_repayment']); ?></td>
                                <td class="status <?php echo strtolower($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['loan_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <script src="script.js"></script>
                </table>
            <?php else: ?>
                <p>No loan accounts found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
