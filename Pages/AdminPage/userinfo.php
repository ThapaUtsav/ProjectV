<?php
// Start the session
session_start();

// Check if the user is logged in (e.g., by checking if 'userID' exists in session)
if (!isset($_SESSION['userID'])) {
    header("Location: ../Userlogin/Form_login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete functionality (make sure to use a prepared statement to avoid SQL injection)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Validate the delete ID (ensure it's a number)
    if (is_numeric($delete_id)) {
        $sql = "DELETE FROM users WHERE account_num = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing the delete statement: " . $conn->error);
        }

        // Bind parameters and execute the delete
        $stmt->bind_param("s", $delete_id); // "s" is for string (VARCHAR type in your case)
        if ($stmt->execute()) {
            echo "Record deleted successfully.";
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid user ID.";
    }
}

// Fetch data (use prepared statement to fetch user data)
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result === false) {
    die("Error fetching data: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="adminstyle.css">
    <script src="script.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: skyblue;
            color:white;
        }
    </style>
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

<!-- Main Home Area -->
<div class="subpage">
    <h1>User Information</h1>
    <table>
        <tr>
            <th>Account Number</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Date of Birth</th>
            <th>Phone</th>
            <th>Street</th>
            <th>City</th>
            <th>State</th>
            <th>Account Created On</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["account_num"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["username"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo $row["dob"]; ?></td>
                    <td><?php echo $row["phone"]; ?></td>
                    <td><?php echo $row["street"]; ?></td>
                    <td><?php echo $row["city"]; ?></td>
                    <td><?php echo $row["state"]; ?></td>
                    <td><?php echo $row["acc_date"]; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $row["account_num"]; ?>">Edit</a> |
                        <a href="userinfo.php?delete_id=<?php echo $row["account_num"]; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="11">No records found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <?php $conn->close(); ?>
</div>

<!-- Footer Section -->
<footer>
    &copy; <?php echo date("Y"); ?> Artha Sanjal. All rights reserved.
</footer>

</body>
</html>
