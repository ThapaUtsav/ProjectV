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

// Query to check the most recent loan for the user
$sql = "SELECT * FROM loans WHERE account_number = '$admin_account_number' ORDER BY loan_date DESC LIMIT 1";
$result = $conn->query($sql);

$has_unpaid_loan = false;
if ($result->num_rows > 0) {
    $loan = $result->fetch_assoc();
    // Check if the loan has not been repaid (you can check for a specific condition like loan_status or repayment_status)
    // Here, we're assuming that an unpaid loan will have a NULL or 'unpaid' repayment status (or another condition based on your database)
    if ($loan['loan_status'] != 'paid') {
        $has_unpaid_loan = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Request</title>
    <link rel="stylesheet" href="indexstyle.css">
    <script src="script.js"></script>
</head>
<body class="light-mode">

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

    <div class="subpage">
        <h1>Loan Request</h1>

        <?php if ($has_unpaid_loan): ?>
            <p style="color: red;">You have an unpaid loan. You must pay it off before applying for a new loan.</p>
        <?php else: ?>
            <form id="loanForm" action="processLoanRequest.php" method="POST">
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
                    <option value="mobile">Digital Wallet</option>
                    <option value="banking">Mobile Banking</option>
                </select><br>

                <label for="total_repayment">Total Repayment (with interest):</label>
                <input type="text" id="total_repayment" name="total_repayment" readonly>

                <label for="monthly_repayment">Monthly Repayment:</label>
                <input type="text" id="monthly_repayment" name="monthly_repayment" readonly>

                <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($admin_account_number); ?>">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">

                <button type="submit">Submit Loan Request</button>
            </form>
        <?php endif; ?>
    </div>

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
