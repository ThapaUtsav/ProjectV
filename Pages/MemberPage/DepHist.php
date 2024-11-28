<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$dbname = 'arthasanjal';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION['userID'];
$month = isset($_POST['month']) ? $_POST['month'] : '';
$year = isset($_POST['year']) ? $_POST['year'] : '';

// Default query to fetch all deposits, excluding those with "rejected" in remarks
$sql = "SELECT * FROM payments WHERE account_number = '$userID' AND remarks NOT LIKE '%rejecte%'";
if ($month && $year) {
    $sql .= " AND MONTH(date) = '$month' AND YEAR(date) = '$year'";
} elseif ($month) {
    $sql .= " AND MONTH(date) = '$month'";
} elseif ($year) {
    $sql .= " AND YEAR(date) = '$year'";
}

$sql .= " ORDER BY date DESC";  // Order by most recent deposit

$result = $conn->query($sql);

$total_sql = $sql; 
$total_result = $conn->query($total_sql);
$total_deposit = 0;
if ($total_result && $total_result->num_rows > 0) {
    while ($row = $total_result->fetch_assoc()) {
        $total_deposit += $row['payment_amount'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="light-mode">
<div class="sidebar" id="sidebar">
        <a href="memberpage.html">Home</a>
        <a href="profile.php">My Profile</a>
    
        <a href="javascript:void(0);" onclick="toggleSubmenu('account-information')">Account Information</a>
        <div class="submenu" id="account-information">
            <a href="../../userfinance/index.php">Deposit </a>
            <a href="../../userfinance/loanindex.php">Loan </a>
        </div>
    
        <a href="javascript:void(0);" onclick="toggleSubmenu('services')">Services</a>
        <div class="submenu" id="services">
            <a href="reqaccstatement.php">Loan Repayment</a>
            <a href="DepHist.php">Deposit History</a>
        </div>
    
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

    <div class="subpage">
        <h1>Deposit History</h1>
        <form method="POST" class="subpage">
            <label for="month">Month:</label>
            <select name="month" id="month">
                <option value="">Select Month</option>
                <option value="1" <?php echo ($month == '1') ? 'selected' : ''; ?>>January</option>
                <option value="2" <?php echo ($month == '2') ? 'selected' : ''; ?>>February</option>
                <option value="3" <?php echo ($month == '3') ? 'selected' : ''; ?>>March</option>
                <option value="4" <?php echo ($month == '4') ? 'selected' : ''; ?>>April</option>
                <option value="5" <?php echo ($month == '5') ? 'selected' : ''; ?>>May</option>
                <option value="6" <?php echo ($month == '6') ? 'selected' : ''; ?>>June</option>
                <option value="7" <?php echo ($month == '7') ? 'selected' : ''; ?>>July</option>
                <option value="8" <?php echo ($month == '8') ? 'selected' : ''; ?>>August</option>
                <option value="9" <?php echo ($month == '9') ? 'selected' : ''; ?>>September</option>
                <option value="10" <?php echo ($month == '10') ? 'selected' : ''; ?>>October</option>
                <option value="11" <?php echo ($month == '11') ? 'selected' : ''; ?>>November</option>
                <option value="12" <?php echo ($month == '12') ? 'selected' : ''; ?>>December</option>
            </select>

            <label for="year">Year:</label>
            <select name="year" id="year">
                <option value="">Select Year</option>
                <?php
                $result_years = $conn->query("SELECT DISTINCT YEAR(date) AS year FROM payments ORDER BY year DESC");
                while ($year_row = $result_years->fetch_assoc()) {
                    $selected = ($year == $year_row['year']) ? 'selected' : '';
                    echo "<option value='{$year_row['year']}' $selected>{$year_row['year']}</option>";
                }
                ?>
            </select>
            <button type="submit">Filter</button>
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="transaction-history">
                <h2>Recent Deposits</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['payment_amount']); ?></td>
                                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="total-deposit">
                <h3>Total Deposit: <?php echo htmlspecialchars($total_deposit); ?></h3>
            </div>
        <?php else: ?>
            <p>No deposit history found for the selected filter.</p>
        <?php endif; ?>
    </div>
    <script src="memscript.js"></script>

    <?php
    $conn->close();
    ?>
</body>
</html>
