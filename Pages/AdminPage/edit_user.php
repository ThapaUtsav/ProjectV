<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

$user = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE account_num = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update user code (same as your existing code)
    // ... (This is the existing update code you posted)
}

// Delete User Code
if (isset($_GET['delete']) && $_GET['delete'] == '1' && $user) {
    // Delete the user and cascade delete all related records
    $stmt = $conn->prepare("DELETE FROM users WHERE account_num = ?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        // After deleting user, redirect to user list page
        header("Location: userinfo.php");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="adminstyle.css">
    <script src="script.js"></script>
    <script src="formvalidation.js"></script>
    <script type="text/javascript">
        function confirmDelete() {
            var result = confirm("Are you sure you want to delete this user? All associated data, including loans, payments, and personal information, will be permanently deleted.");
            if (result) {
                // If confirmed, proceed with the deletion
                window.location.href = 'edit_user.php?id=<?php echo $user['account_num']; ?>&delete=1';
            } else {
                // If canceled, do nothing and stay on the page
                return false;
            }
        }
    </script>
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
            <a href="../../finance/index.php">Deposit </a>
            <a href="../../finance/loanindex.php">Loan </a>
        </div>

        <!-- Loan Repayment Section -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('loanRepaymentSubmenu')">Repayment</a>
        <div class="submenu" id="loanRepaymentSubmenu">
            <a href="loan_repayment.php">Loan Repayments</a>
        </div>

        <!-- Reports Section -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('reportsSubmenu')">Reports</a>
        <div class="submenu" id="reportsSubmenu">
            <a href="monthlyreport.php">Monthly Reports</a>
            <a href="annualreport.php">Annual Reports</a>
            <a href="../../finance/loanreport.php">Loan Reports </a>
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

    <!-- Form Area -->
    <form action="edit_user.php?id=<?php echo htmlspecialchars($user['account_num']); ?>" method="POST">
        <div class="subpage">
            <h1>Edit User Profile</h1>

            <!-- User Info Form Section -->
            <!-- Personal Information Section -->

            <h2>Personal Information</h2>
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Date of Birth:</label>
            <input type="date" name="dob" value="<?php echo $user['dob']; ?>">

            <label>Phone Number:</label>
            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

            <h2>Address</h2>
            <label>Street Address:</label>
            <input type="text" name="street" value="<?php echo htmlspecialchars($user['street']); ?>" required>

            <label>City:</label>
            <input type="text" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" required>

            <label>State:</label>
            <input type="text" name="state" value="<?php echo htmlspecialchars($user['state']); ?>" required>

            <label>Created On:</label>
            <input type="date" name="acc_date" value="<?php echo $user['acc_date']; ?>">

            <!-- Submit Button -->
            <button type="submit">Submit</button>
        </div>
    </form>



</body>
</html>
