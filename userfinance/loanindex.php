<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$admin_account_number = $_SESSION['userID'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "arthasanjal";

// Create a connection to the database
$conn = new mysqli($servername, $dbusername, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user has any unpaid loans
$sql_check_loans = "SELECT * FROM loans WHERE created_by = ? AND status != 'Paid'";
$stmt_check = $conn->prepare($sql_check_loans);
$stmt_check->bind_param("s", $admin_account_number);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

// If the user has an unpaid loan, prevent loan request
if ($result_check->num_rows > 0) {
    // Set a flag to show the alert and prevent loan form
    $has_unpaid_loans = true;
} else {
    $has_unpaid_loans = false;
}

$today_date = date('Y-m-d');

if (isset($_SESSION['deposit_message'])) {
    echo "<p style='color: green;'>".$_SESSION['deposit_message']."</p>";
    unset($_SESSION['deposit_message']);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Loan Request</title>
  <link rel="stylesheet" href="usstyle.css">
</head>
<body class="light-mode">

    <!-- Sidebar Navigation -->
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
            <a href="../Pages/MemberPage/DepHist.php">Deposit History</a>
        </div>
        <a href="../Pages/MemberPage/help.php">Support/Help</a>
        <a href="../Pages/MemberPage/signout.php">Sign Out</a>
    </div>

    <!-- Header Section -->
    <header>
        <div class="hamburger-menu" onclick="toggleSidebar()">&#9776;</div>
        <div class="nav-links">
            <a href="../Pages/MemberPage/notification.php">Notifications</a>
            <div class="theme-link" onclick="toggleThemeDropdown()">Theme</div>
            <div class="theme-dropdown" id="theme-dropdown">
                <a href="#" onclick="switchMode('light')">Light Mode</a>
                <a href="#" onclick="switchMode('dark')">Dark Mode</a>
            </div>
        </div>
    </header>
    
    <!-- Loan Request Form Section -->
    <h1>Request Loan</h1>
    <div class="subpage">

        <?php if ($has_unpaid_loans): ?>
            <!-- Show an alert if the user has an unpaid loan -->
            <script>
                alert("You have an unpaid loan. Please settle the previous loan before requesting a new one.");
                window.location.href = '../Pages/MemberPage/memberpage.html'; // Redirect to home page
            </script>
        <?php endif; ?>

        <!-- Loan Request Form (Only visible if no unpaid loans) -->
        <form id="loanForm" action="submitLoan.php" method="POST" <?php echo $has_unpaid_loans ? 'style="display:none;"' : ''; ?>>
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

            <button type="submit">Submit Request</button>
        </form>
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
 <script src="userfscript.js" defer></script>
</body>
</html>
