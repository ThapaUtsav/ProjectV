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

// Fetch the user's loans
$sql = "SELECT loan_id, loan_amount, interest_rate, repayment_period, total_repayment, monthly_repayment, loan_date, status, created_by FROM loans WHERE account_number = '$admin_account_number'";
$result = $conn->query($sql);

$total_loan_amount = 0;  // Variable to track the total loan amount
$total_interest_earned = 0;  // Variable to track the total interest earned
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Report</title>
    <link rel="stylesheet" href="indexstyle.css">
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

    <!-- Main Content Area -->
    <div class="subpage">
        <h1>Loan Report</h1>
        <div class="loan-table-container">
            <?php if ($result->num_rows > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Loan Amount (NPR)</th>
                            <th>Interest Rate (%)</th>
                            <th>Repayment Period (Months)</th>
                            <th>Total Repayment (NPR)</th>
                            <th>Monthly Repayment (NPR)</th>
                            <th>Status</th>
                            <th>Loan Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <?php
                                // Calculate the interest earned
                                $interest_earned = $row['total_repayment'] - $row['loan_amount'];
                                $total_loan_amount += $row['loan_amount']; // Add loan amount to total
                                $total_interest_earned += $interest_earned; // Add interest earned to total
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                <td><?php echo number_format($row['loan_amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['interest_rate']); ?></td>
                                <td><?php echo htmlspecialchars($row['repayment_period']); ?></td>
                                <td><?php echo number_format($row['total_repayment'], 2); ?></td>
                                <td><?php echo number_format($row['monthly_repayment'], 2); ?></td>
                                <td class="status <?php echo strtolower($row['status']); ?>">
                                    <?php
                                        if (strtolower($row['status']) == "paid") {
                                            echo '<span style="color: green;">' . htmlspecialchars($row['status']) . '</span>';
                                        } else {
                                            echo htmlspecialchars($row['status']);
                                        }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['loan_date']); ?></td>
                            </tr>
                            <tr class="loan-summary-row">
                                <td colspan="8">
                                    <strong>Total Loan Amount:</strong> NPR <?php echo number_format($row['loan_amount'], 2); ?><br>
                                    <strong>Total Interest Earned:</strong> NPR <?php echo number_format($interest_earned, 2); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No loan accounts found.</p>
            <?php endif; ?>
        </div>
    </div>
<script src="script.js"></script>
</body>
</html>

<?php
$conn->close();
?>
