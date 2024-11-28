<?php
session_start();

$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$selectedUsername = isset($_GET['username']) ? $_GET['username'] : '';

$yearsSql = "SELECT DISTINCT YEAR(date) AS year FROM payments ORDER BY year DESC";
$yearsResult = $conn->query($yearsSql);

// Modify the SQL query to only select 'approve' payments
$sql = "SELECT * FROM payments WHERE MONTH(date) = ? AND YEAR(date) = ? AND remarks = 'approve'";
$params = [$selectedMonth, $selectedYear];

if ($selectedUsername) {
    $sql .= " AND username = ?";
    $params[] = $selectedUsername;
}

$stmt = $conn->prepare($sql);

if ($selectedUsername) {
    $stmt->bind_param("sss", ...$params);
} else {
    $stmt->bind_param("ss", $params[0], $params[1]);
}

$stmt->execute();
$result = $stmt->get_result();
$payments = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
$totalDeposit = 0;

foreach ($payments as $payment) {
    $totalDeposit += $payment['payment_amount'];
}

$stmt->close();

$usernamesSql = "SELECT DISTINCT username FROM users";
$usernamesResult = $conn->query($usernamesSql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report</title>
    <link rel="stylesheet" href="adminstyle.css">
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
            <a href="../finance/loanindex.php">Loan </a>
        </div>
        <a href="javascript:void(0);" onclick="toggleSubmenu('loanRepaymentSubmenu')">Repayment</a>
        <div class="submenu" id="loanRepaymentSubmenu">
            <a href="loan_repayment.php">Loan Repayments</a>
        </div>
        <a href="javascript:void(0);" onclick="toggleSubmenu('reportsSubmenu')">Reports</a>
        <div class="submenu" id="reportsSubmenu">
            <a href="monthlyreport.php">Monthly Reports</a>
            <a href="annualreport.php">Annual Reports</a>
            <a href="../../finance/loanreport.php">Loan Reports </a>
        </div>
        <a href="help.php">Support/Help</a>
        <a href="signout.php">Sign Out</a>
    </div>

    <header>
        <div class="hamburger-menu" onclick="toggleSidebar()">&#9776;</div>
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
        <h2>Monthly Report</h2>

        <form action="monthlyreport.php" method="GET">
            <label for="month">Select Month:</label>
            <select name="month" id="month">
                <option value="01" <?php if ($selectedMonth == '01') echo 'selected'; ?>>January</option>
                <option value="02" <?php if ($selectedMonth == '02') echo 'selected'; ?>>February</option>
                <option value="03" <?php if ($selectedMonth == '03') echo 'selected'; ?>>March</option>
                <option value="04" <?php if ($selectedMonth == '04') echo 'selected'; ?>>April</option>
                <option value="05" <?php if ($selectedMonth == '05') echo 'selected'; ?>>May</option>
                <option value="06" <?php if ($selectedMonth == '06') echo 'selected'; ?>>June</option>
                <option value="07" <?php if ($selectedMonth == '07') echo 'selected'; ?>>July</option>
                <option value="08" <?php if ($selectedMonth == '08') echo 'selected'; ?>>August</option>
                <option value="09" <?php if ($selectedMonth == '09') echo 'selected'; ?>>September</option>
                <option value="10" <?php if ($selectedMonth == '10') echo 'selected'; ?>>October</option>
                <option value="11" <?php if ($selectedMonth == '11') echo 'selected'; ?>>November</option>
                <option value="12" <?php if ($selectedMonth == '12') echo 'selected'; ?>>December</option>
            </select>

            <label for="year">Select Year:</label>
            <select name="year" id="year">
                <?php
                while ($yearRow = $yearsResult->fetch_assoc()) {
                    $year = $yearRow['year'];
                    $selected = ($selectedYear == $year) ? 'selected' : '';
                    echo "<option value='$year' $selected>$year</option>";
                }
                ?>
            </select>

            <label for="username">Select Username:</label>
            <select name="username" id="username">
                <option value="">All Users</option>
                <?php
                while ($row = $usernamesResult->fetch_assoc()) {
                    $selected = ($selectedUsername == $row['username']) ? 'selected' : '';
                    echo "<option value='{$row['username']}' $selected>{$row['username']}</option>";
                }
                ?>
            </select>

            <button type="submit">View Report</button>
        </form>

        <h3>Report for <?php echo date('F, Y', strtotime($selectedYear . '-' . $selectedMonth . '-01')); ?></h3>

        <table border="1" class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($payments) > 0) {
                    foreach ($payments as $payment) {
                        echo "<tr>
                                <td>{$payment['username']}</td>
                                <td>{$payment['date']}</td>
                                <td>{$payment['remarks']}</td>
                                <td>{$payment['payment_method']}</td>
                                <td>{$payment['payment_amount']}</td>
                              </tr>";
                    }
                    echo "<tr>
                            <td colspan='4'><strong>Total Deposit</strong></td>
                            <td><strong>{$totalDeposit}</strong></td>
                          </tr>";
                } else {
                    echo "<tr><td colspan='6'>No deposits found for this month.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="script.js"></script>
</body>

</html>
