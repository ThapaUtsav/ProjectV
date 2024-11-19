<?php
session_start();

if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$user_account_number = $_SESSION['userID'];

$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM loans WHERE account_number = '$user_account_number' AND status != 'Paid' ORDER BY loan_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $loan = $result->fetch_assoc();
} else {
    echo "<script>alert('No loan found for this user.'); window.location.href = 'memberpage.html';</script>";
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
    <link rel="stylesheet" href="style.css">
    <script src="memscript.js" defer></script>
</head>
<body class="light-mode">

<div class="sidebar" id="sidebar">
        <!-- Home and Profile -->
        <a href="memberpage.html">Home</a>
        <a href="profile.php">My Profile</a>
    
        <!-- Account Information with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('account-information')">Account Information</a>
        <div class="submenu" id="account-information">
            <a href="../../userfinance/index.php">Deposit Amount</a>
            <a href="../../userfinance/loanindex.php">Loan Amount</a>
        </div>
    
        <!-- Services with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('services')">Services</a>
        <div class="submenu" id="services">
            <a href="reqaccstatement.php">Loan Repayment</a>
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
            <a href="notification.php">Notifications</a>
            <div class="theme-link" onclick="toggleThemeDropdown()">Theme</div>
            <div class="theme-dropdown" id="theme-dropdown">
                <a href="#" onclick="switchMode('light')">Light Mode</a>
                <a href="#" onclick="switchMode('dark')">Dark Mode</a>
            </div>
        </div>
    </header>

    <div class="home-area">
        <h2>Loan Repayment</h2>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <h3>Loan Details</h3>
        <table>
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
        <form action="reqaccstatement.php" method="POST">
            <label for="repayment_amount">Repayment Amount (Max: <?php echo $loan['monthly_repayment']; ?>):</label>
            <input type="number" id="repayment_amount" name="repayment_amount" min="0" max="<?php echo $loan['monthly_repayment']; ?>" step="any" required>

            <button type="submit">Make Repayment</button>
        </form>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Artha Sanjal. All rights reserved.
    </footer>

</body>
</html>