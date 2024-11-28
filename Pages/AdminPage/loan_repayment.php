<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$username = $_SESSION['username'];

$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM loans WHERE created_by = '$username' AND status != 'Paid' ORDER BY loan_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $loan = $result->fetch_assoc();
} else {
    echo "<script>alert('No loan found for this user.'); window.location.href = 'admin_page.html';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['repayment_amount'])) {
    $repayment_amount = floatval($_POST['repayment_amount']);
    $loan_id = $loan['loan_id'];

    if ($repayment_amount <= 0 || $repayment_amount > $loan['monthly_repayment']) {
        $error_message = "Invalid repayment amount. Please enter a valid amount.";
    } else {
        $repayment_date = date('Y-m-d');

        $stmt = $conn->prepare("INSERT INTO repayments (loan_id, repayment_amount, repayment_date) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Failed to prepare the statement. Error: " . $conn->error);
        }

        $stmt->bind_param("iis", $loan_id, $repayment_amount, $repayment_date);
        $stmt->execute();

        $remaining_repayment = $loan['total_repayment'] - $repayment_amount;
        if ($remaining_repayment <= 0) {
            $stmt = $conn->prepare("UPDATE loans SET status = 'Paid' WHERE loan_id = ?");
            if (!$stmt) {
                die("Failed to prepare the update statement. Error: " . $conn->error);
            }

            $stmt->bind_param("i", $loan_id);
            $stmt->execute();
        }

        $success_message = "Repayment successfully made!";
        echo "<script>alert('$success_message'); window.location.href = 'admin_page.html';</script>";
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Repayment</title>
    <link rel="stylesheet" href="adminstyle.css">
    <script src="script.js" defer></script>
</head>
<body class="light-mode">

    <div class="sidebar" id="sidebar">
        <a href="admin_page.html">Home</a>
        <a href="javascript:void(0);" onclick="toggleSubmenu('userManagementSubmenu')">User Management</a>
        <div class="submenu" id="userManagementSubmenu">
            <a href="add_user.php">Add New User</a>
            <a href="userinfo.php">Manage User Information</a>
        </div>

        <a href="javascript:void(0);" onclick="toggleSubmenu('accountManagementSubmenu')">Account Management</a>
        <div class="submenu" id="accountManagementSubmenu">
            <a href="../../finance/index.php">Deposit </a>
            <a href="../../finance/loanindex.php">Loan </a>
        </div>

        <a href="javascript:void(0);" onclick="toggleSubmenu('loanRepaymentSubmenu')">Loan Repayment</a>
        <div class="submenu" id="loanRepaymentSubmenu">
            <a href="loan_repayment.php">Manage Loan Repayments</a>
        </div>

        <a href="javascript:void(0);" onclick="toggleSubmenu('reportsSubmenu')">Reports</a>
        <div class="submenu" id="reportsSubmenu">
            <a href="monthlyreport.php">Monthly Reports</a>
            <a href="annualreport.php">Annual Reports</a>
            <a href="../../finance/loanreport.php">Loan Reports</a>
        </div>

        <a href="help.php">Support/Help</a>
        <a href="signout.php">Sign Out</a>
    </div>

    <header>
        <div class="hamburger-menu" onclick="toggleSidebar()">
            &#9776;
        </div>
        <div class="nav-links">
            <a href="notification.php">Notifications</a>
            <div class="theme-link" onclick="toggleThemeDropdown()">Theme</div>
            <div class="theme-dropdown" id="theme-dropdown">
                <a href="#" onclick="switchMode('light')">Light Mode</a>
                <a href="#" onclick="switchMode('dark')">Dark Mode</a>
            </div>
        </div>
    </header>

    <div class="subpage">
        <h2>Loan Repayment</h2>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <h3>Loan Details</h3>
        <table class="subpage" align="center">
            <tr>
                <th>Loan Amount</th>
                <td><?php echo htmlspecialchars($loan['loan_amount']); ?></td>
            </tr>
            <tr>
                <th>Interest Rate (%)</th>
                <td><?php echo htmlspecialchars($loan['interest_rate']); ?></td>
            </tr>
            <tr>
                <th>Repayment Period (Months)</th>
                <td><?php echo htmlspecialchars($loan['repayment_period']); ?></td>
            </tr>
            <tr>
                <th>Total Repayment</th>
                <td><?php echo htmlspecialchars($loan['total_repayment']); ?></td>
            </tr>
            <tr>
                <th>Monthly Repayment</th>
                <td><?php echo htmlspecialchars($loan['monthly_repayment']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($loan['status']); ?></td>
            </tr>
        </table>

        <h3>Make Repayment</h3>
        <form action="loan_repayment.php" method="POST">
            <label for="repayment_amount">Repayment Amount (Max: <?php echo $loan['monthly_repayment']; ?>):</label>
            <input type="number" id="repayment_amount" name="repayment_amount" min="0" max="<?php echo $loan['monthly_repayment']; ?>" step="any" required>

            <button type="submit">Make Repayment</button>
        </form>
    </div>

</body>
</html>
