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

// Get the user information based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM user_profiles WHERE id = $id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}

// Update the user information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $accountType = $_POST['accountType'];
    $accDate = $_POST['accDate'];

    $sql = "UPDATE user_profiles SET
                name='$name', username='$username', email='$email', dob='$dob', phone='$phone',
                street='$street', city='$city', state='$state', zip='$zip',
                accountType='$accountType', accDate='$accDate'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        header("Location: userinfo.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
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
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <script src="formvalidation.js"></script>
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

    <!-- Form Area -->
    <form action="edit_user.php" method="POST">
    
    <div class="subpage">
        <h1>Edit User Profile</h1>

        <!-- Personal Information Section -->
        <h2>Personal Information</h2>
        
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

        <label>Username:</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

        <label>Date of Birth:</label>
        <input type="date" name="dob" value="<?php echo $user['dob']; ?>">

        <label>Phone Number:</label>
        <input type="tel" name="phone" value="<?php echo $user['phone']; ?>">

        <!-- Address Section -->
        <h2>Address</h2>
        <label>Street Address:</label>
        <input type="text" name="street" value="<?php echo $user['street']; ?>" required>

        <label>City:</label>
        <input type="text" name="city" value="<?php echo $user['city']; ?>" required>

        <label>State:</label>
        <input type="text" name="state" value="<?php echo $user['state']; ?>" required>

        <label>ZIP Code:</label>
        <input type="text" name="zip" value="<?php echo $user['zip']; ?>" required>

        <!-- Account Status Section -->
        <h2>Account Status</h2>
        
        <label>Account Type:</label><br>
        <select name="accountType">
        <option value="saving" <?php if ($user['accountType'] == 'saving') echo 'selected'; ?>>Saving</option>
        <option value="loan" <?php if ($user['accountType'] == 'loan') echo 'selected'; ?>>Loan</option>
        </select><br>

        <label>Account Created On:</label>
        <input type="date" name="accDate" value="<?php echo $user['accDate']; ?>">

        <!-- Submit Button -->
        <button type="submit">Submit</button>
    </div>
</form>

<!-- Footer Section -->
<footer>
        &copy; <?php echo date("Y");?> Artha Sanjal. All rights reserved.
    </footer>

</body>
</html>
