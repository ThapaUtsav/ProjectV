<?php
session_start();

// Check if the user is logged in and session variable 'userID' is set
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$admin_account_number = $_SESSION['userID']; // Get the admin account number from the session

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

$sql_deposits = "SELECT * FROM payments WHERE remarks IS NULL OR remarks = ''";
$result_deposits = $conn->query($sql_deposits);
$sql_loans = "SELECT * FROM loans WHERE status = 'Pending' OR status IS NULL";
$result_loans = $conn->query($sql_loans);

// Store pending deposits
$pending_deposits = [];
if ($result_deposits->num_rows > 0) {
    while($row = $result_deposits->fetch_assoc()) {
        $pending_deposits[] = $row;
    }
}

// Store pending loans
$pending_loans = [];
if ($result_loans->num_rows > 0) {
    while($row = $result_loans->fetch_assoc()) {
        $pending_loans[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approvals</title>
    <link rel="stylesheet" href="adminstyle.css">
    <script src="script.js"></script>
</head>
<body class="light-mode">
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <a href="admin_page.html">Home</a>

        <!-- User Management Section -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('userManagementSubmenu')">User Management</a>
        <div class="submenu" id="userManagementSubmenu">
            <a href="add_user.php">Add New User</a>
            <a href="userinfo.php">Manage User Information</a>
        </div>

        <!-- Account Management Section -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('accountManagementSubmenu')">Account Management</a>
        <div class="submenu" id="accountManagementSubmenu">
            <a href="../../finance/index.php">Deposit </a>
            <a href="../../finance/loanindex.php">Loan </a>
        </div>

        <!-- Loan Repayment Section -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('loanRepaymentSubmenu')">Repayment</a>
        <div class="submenu" id="loanRepaymentSubmenu">
            <a href="loan_repayment.php">Loan Repayments</a>
        </div>

        <!-- Reports Section -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('reportsSubmenu')">Reports</a>
        <div class="submenu" id="reportsSubmenu">
            <a href="monthlyreport.php">Monthly Reports</a>
            <a href="annualreport.php">Annual Reports</a>
        </div>

        <!-- Support and Sign Out -->
        <a href="help.php">Support/Help</a>
        <a href="signout.php">Sign Out</a>
    </div>

    <!-- Header Section -->
    <header>
        <div class="hamburger-menu" onclick="toggleSidebar()">
            &#9776; <!-- Hamburger icon -->
        </div>
        <div class="nav-links">
            <a href="#">Pending Approvals</a>
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
    <main>
        <div class="container">
            <h2>Pending Deposit Notifications</h2>
            
            <?php if (empty($pending_deposits)): ?>
                <p>No pending deposits at the moment.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Account Number</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_deposits as $deposit): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($deposit['account_number']); ?></td>
                                <td><?php echo htmlspecialchars($deposit['date']); ?></td>
                                <td><?php echo htmlspecialchars($deposit['payment_amount']); ?></td>
                                <td><?php echo htmlspecialchars($deposit['payment_method']); ?></td>
                                <td>
                                    <form method="post" action="approve_reject.php">
                                        <input type="hidden" name="payment_id" value="<?php echo $deposit['payment_id']; ?>">
                                        <button type="submit" name="status" value="approved" class="btn approve-btn">Approve</button>
                                        <button type="submit" name="status" value="rejected" class="btn reject-btn">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <h2>Pending Loan Approvals</h2>
            
            <?php if (empty($pending_loans)): ?>
                <p>No pending loans at the moment.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Account Number</th>
                            <th>Loan Amount</th>
                            <th>Interest Rate</th>
                            <th>Repayment Period (Months)</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_loans as $loan): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($loan['account_number']); ?></td>
                                <td><?php echo htmlspecialchars($loan['loan_amount']); ?></td>
                                <td><?php echo htmlspecialchars($loan['interest_rate']); ?></td>
                                <td><?php echo htmlspecialchars($loan['repayment_period']); ?></td>
                                <td><?php echo htmlspecialchars($loan['payment_method']); ?></td>
                                <td>
                                    <form method="post" action="admin_approve_reject_loan.php">
                                        <input type="hidden" name="loan_id" value="<?php echo $loan['loan_id']; ?>">
                                        <button type="submit" name="status" value="approved" class="btn approve-btn">Approve</button>
                                        <button type="submit" name="status" value="rejected" class="btn reject-btn">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Artha Sanjal. All rights reserved.
    </footer>
</body>
</html>
