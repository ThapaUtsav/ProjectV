<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arthasanjal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current year or selected year
$currentYear = date('Y');

// Fetch all unique usernames for the dropdown
$usernameSql = "SELECT DISTINCT username FROM payments";
$usernameResult = $conn->query($usernameSql);
$usernames = [];
if ($usernameResult->num_rows > 0) {
    while ($row = $usernameResult->fetch_assoc()) {
        $usernames[] = $row['username'];
    }
}

// Check if a year is selected from the form
$selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear;

// Check if a username is selected for search
$selectedUsername = isset($_GET['username']) ? $_GET['username'] : '';

// Build the query based on selected year and username
$sql = "SELECT username, payment_amount, date, payment_method, remarks 
        FROM payments WHERE YEAR(date) = ? AND remarks = 'approve'"; // Filter by 'approve'

$params = [$selectedYear];

if ($selectedUsername != '') {
    $sql .= " AND username LIKE ?";
    $params[] = "%" . $selectedUsername . "%";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($params)), ...$params); 
$stmt->execute();
$result = $stmt->get_result();

$payments = [];
$totalDeposit = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
        $totalDeposit += $row['payment_amount'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annual Report</title>
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
            <a href="../../finance/index.php">Deposit</a>
            <a href="../../finance/loanindex.php">Loan</a>
        </div>
        <a href="javascript:void(0);" onclick="toggleSubmenu('loanRepaymentSubmenu')">Repayment</a>
        <div class="submenu" id="loanRepaymentSubmenu">
            <a href="loan_repayment.php">Loan Repayments</a>
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

    <div class="main-content">
        <h2>Annual Report - <?php echo $selectedYear; ?></h2>

        <div class="subpage">
        <form method="GET" action="annualreport.php">
            <label for="year">Select Year:</label>
            <select name="year" id="year" onchange="this.form.submit()">
                <?php
                $startYear = 2020;
                $endYear = $currentYear;
                for ($year = $startYear; $year <= $endYear; $year++) {
                    $selected = ($year == $selectedYear) ? 'selected' : '';
                    echo "<option value='$year' $selected>$year</option>";
                }
                ?>
            </select>

            <label for="username">Search by Username:</label>
            <select name="username" id="username">
                <option value="">All Users</option>
                <?php
                foreach ($usernames as $username) {
                    $selected = ($username == $selectedUsername) ? 'selected' : '';
                    echo "<option value='$username' $selected>$username</option>";
                }
                ?>
            </select>

            <button type="submit">View Report</button>
        </form>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Date</th>
                    <th>Remarks</th>
                    <th>Payment Method</th>
                    <th>Payment</th>
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
                    echo "<tr class='total-row'>
                            <td colspan='4'><strong>Total Deposit</strong></td>
                            <td><strong>{$totalDeposit}</strong></td>
                          </tr>";
                } else {
                    echo "<tr><td colspan='5'>No payments found for the selected year and username.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        function toggleSubmenu(id) {
            document.getElementById(id).classList.toggle('active');
        }

        function toggleThemeDropdown() {
            document.getElementById('theme-dropdown').classList.toggle('show');
        }

        function switchMode(mode) {
            if (mode === 'dark') {
                document.body.classList.add('dark-mode');
                document.body.classList.remove('light-mode');
            } else {
                document.body.classList.add('light-mode');
                document.body.classList.remove('dark-mode');
            }
        }
    </script>
</body>
<script src="script.js"></script>
</html>

<?php
// Close the prepared statement and connection
$stmt->close();
$conn->close();
?>
