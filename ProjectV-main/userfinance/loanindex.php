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

$sql = "SELECT loan_id, loan_amount, interest_rate, repayment_period, total_repayment, monthly_repayment, loan_date, status, created_by FROM loans WHERE account_number = '$admin_account_number'";
$result = $conn->query($sql);
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
            <a href="../Pages/MemberPage/notification.php">Notifications</a>
            <div class="theme-link" onclick="toggleThemeDropdown()">
                Theme
            </div>
            <div class="theme-dropdown" id="theme-dropdown">
                <a href="#" onclick="switchMode('light')">Light Mode</a>
                <a href="#" onclick="switchMode('dark')">Dark Mode</a>
            </div>
        </div>
    </header>

    <h1>Request Loan</h1>
    <div class="subpage">
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

        <button type="submit">Submit Request</button>
    </form>
    </div>
    <div>
        <h2>Current Loan Accounts</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
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
            </table>
        <?php else: ?>
            <p>No loan accounts found.</p>
        <?php endif; ?>
    </div>
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
