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
$sql = "SELECT * FROM payments WHERE YEAR(date) = ?";
$params = [$selectedYear];

if ($selectedUsername != '') {
    $sql .= " AND username LIKE ?";
    $params[] = "%" . $selectedUsername . "%";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($params)), ...$params); // Dynamically bind parameters
$stmt->execute();
$result = $stmt->get_result();

// Check if payments are found
$payments = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
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
            <a href="../../finance/index.php">Deposit Amount</a>
            <a href="../../finance/loanindex.php">Loan Account Management</a>
        </div>

        <!-- Loan Repayment Section -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('loanRepaymentSubmenu')">Loan Repayment</a>
        <div class="submenu" id="loanRepaymentSubmenu">
            <a href="loan_repayment.php">Manage Loan Repayments</a>
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
            &#9776;
        </div>
        <div class="nav-links">
            <a href="notification.php">Notifications</a>
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
    <div class="main-content">
        <h2>Annual Report - <?php echo $selectedYear; ?></h2>

        <!-- Year and Username Selector Form -->
        <form method="GET" action="annualreport.php">
            <label for="year">Select Year:</label>
            <select name="year" id="year" onchange="this.form.submit()">
                <?php
                // Display years from 2020 to the current year
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
                // Display usernames as options in the dropdown
                foreach ($usernames as $username) {
                    $selected = ($username == $selectedUsername) ? 'selected' : '';
                    echo "<option value='$username' $selected>$username</option>";
                }
                ?>
            </select>

            <input type="submit" value="Search">
        </form>

        <!-- Table to display report -->
        <table border="1">
            <thead>
                <tr>
                    <th>Account Number</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Username</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($payments) > 0) {
                    foreach ($payments as $payment) {
                        echo "<tr>
                                <td>{$payment['account_number']}</td>
                                <td>{$payment['date']}</td>
                                <td>{$payment['payment_amount']}</td>
                                <td>{$payment['payment_method']}</td>
                                <td>{$payment['remarks']}</td>
                                <td>{$payment['username']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No payments found for the selected year and username.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; <?php echo date("Y"); ?> Artha Sanjal. All rights reserved.
    </footer>

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Toggle Submenu
        function toggleSubmenu(id) {
            document.getElementById(id).classList.toggle('active');
        }

        // Theme switching logic
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
