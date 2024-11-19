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

// Fetch the user's loans, including the outstanding balance
$sql = "SELECT loan_id, loan_amount, interest_rate, repayment_period, total_repayment, monthly_repayment, loan_date, status, created_by, outstanding_balance FROM loans WHERE account_number = '$admin_account_number'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Loan Deposit and Management</title>
  <link rel="stylesheet" href="finnstyle.css">
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
        <a href="javascript:void(0);" onclick="toggleSubmenu('accountManagementSubmenu')">Account Management</a>
        <div class="submenu" id="accountManagementSubmenu">
            <a href="index.php">Deposit Amount</a>
            <a href="loanindex.php">Loan Account Management</a>
        </div>
        <a href="javascript:void(0);" onclick="toggleSubmenu('reportsSubmenu')">Reports</a>
        <div class="submenu" id="reportsSubmenu">
            <a href="../Pages/AdminPage/monthlyreport.php">Monthly Reports</a>
            <a href="../Pages/AdminPage/annualreport.php">Annual Reports</a>
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
            <a href="#">Notifications</a>
            <div class="theme-link" onclick="toggleThemeDropdown()">Theme</div>
            <div class="theme-dropdown" id="theme-dropdown">
                <a href="#" onclick="switchMode('light')">Light Mode</a>
                <a href="#" onclick="switchMode('dark')">Dark Mode</a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <div class="home-area">
        <h1>Loan Deposit</h1>
        <form id="loanForm" action="submitLoan.php" method="POST">
            <label for="loan_date">Loan Date:</label>
            <input type="date" id="loan_date" name="loan_date" value="<?php echo htmlspecialchars($today_date); ?>" required>
            
            <label for="loan_amount">Loan Amount:</label>
            <input type="number" id="loan_amount" name="loan_amount" required>

            <label for="interest_rate">Interest Rate (%):</label>
            <input type="number" id="interest_rate" name="interest_rate" step="0.1" min="0" required>

            <label for="repayment_period">Repayment Period (Months):</label>
            <input type="number" id="repayment_period" name="repayment_period" required>

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="mobile">Mobile</option>
                <option value="banking">Banking</option>
            </select>

            <label for="total_repayment">Total Repayment (with interest):</label>
            <input type="text" id="total_repayment" name="total_repayment" readonly>

            <label for="monthly_repayment">Monthly Repayment:</label>
            <input type="text" id="monthly_repayment" name="monthly_repayment" readonly>

            <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($admin_account_number); ?>">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
            
            <button type="submit">Submit Loan</button>
        </form>

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
                            <th>Outstanding Balance</th> 
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
                                <td><?php echo htmlspecialchars($row['outstanding_balance']); ?></td>
                                <td class="status <?php echo strtolower($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['loan_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No loan accounts found.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Artha Sanjal. All rights reserved.
    </footer>

    <script>
        document.getElementById("loan_amount").addEventListener("input", calculateLoan);
        document.getElementById("interest_rate").addEventListener("input", calculateLoan);
        document.getElementById("repayment_period").addEventListener("input", calculateLoan);

        function calculateLoan() {
            const loanAmount = parseFloat(document.getElementById("loan_amount").value);
            const interestRate = parseFloat(document.getElementById("interest_rate").value);
            const repaymentPeriod = parseInt(document.getElementById("repayment_period").value);

            if (!isNaN(loanAmount) && !isNaN(interestRate) && !isNaN(repaymentPeriod)) {
                const totalRepayment = loanAmount + (loanAmount * (interestRate / 100));
                const monthlyRepayment = totalRepayment / repaymentPeriod;

                document.getElementById("total_repayment").value = totalRepayment.toFixed(2);
                document.getElementById("monthly_repayment").value = monthlyRepayment.toFixed(2);
            }
        }
    </script>

</body>
</html>
