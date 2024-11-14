<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "arthasanjal"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete functionality
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM user_profiles WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch data
$sql = "SELECT * FROM user_profiles";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="style.css">
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
        <a href="adminpage.php">Home</a>
        <a href="javascript:void(0);" onclick="toggleSubmenu('userManagementSubmenu')">User Management</a>
        <div class="submenu" id="userManagementSubmenu">
            <a href="adduser.php">Add New User</a>
            <a href="userinfo.php">Manage User Information</a>
        </div>
        <a href="#" onclick="toggleSubmenu('accountManagementSubmenu')">Account Management</a>
        <div class="submenu" id="accountManagementSubmenu">
            <a href="savingaccount.php">Saving Account Management</a>
            <a href="loanaccount.php">Loan Account Management</a>
        </div>
        <a href="#" onclick="toggleSubmenu('reportsSubmenu')">Reports</a>
        <div class="submenu" id="reportsSubmenu">
            <a href="monthlyreport.php">Monthly Reports</a>
            <a href="annualreport.php">Annual Reports</a>
        </div>
        <a href="help.php">Support/Help</a>
        <a href="signout.php">Sign Out</a>
    </div>

    <!-- Header Section -->
    <header>
        <div class="hamburger-menu" onclick="toggleSidebar()">
            &#9776;
        </div>
        <div class="nav-links">
            <a href="#">Notifications</a>
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
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Date of Birth</th>
        <th>Phone</th>
        <th>Street</th>
        <th>City</th>
        <th>State</th>
        <th>ZIP Code</th>
        <th>Account Type</th>
        <th>Account Created On</th>
        <th>Actions</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["name"]; ?></td>
                <td><?php echo $row["username"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
                <td><?php echo $row["dob"]; ?></td>
                <td><?php echo $row["phone"]; ?></td>
                <td><?php echo $row["street"]; ?></td>
                <td><?php echo $row["city"]; ?></td>
                <td><?php echo $row["state"]; ?></td>
                <td><?php echo $row["zip"]; ?></td>
                <td><?php echo $row["accountType"]; ?></td>
                <td><?php echo $row["accDate"]; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $row["id"]; ?>">Edit</a> |
                    <a href="userinfo.php?delete_id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="13">No records found.</td>
        </tr>
    <?php endif; ?>
</table>

<?php $conn->close(); ?>


    </div>
    <!-- Footer Section -->
    <footer>
        &copy; <?php echo date("Y");?> Artha Sanjal. All rights reserved.
    </footer>
</body>
</html>

