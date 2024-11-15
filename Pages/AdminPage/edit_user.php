<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arthasanjal";

// Create connection using MySQLi with error reporting
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
    // Log the error and show a user-friendly message
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

$user = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE account_num = ?");
    $stmt->bind_param("s", $id); // 's' for string (account_num is varchar)
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $acc_date = $_POST['acc_date'];

    // Sanitize inputs to prevent XSS attacks
    $name = htmlspecialchars($name);
    $username = htmlspecialchars($username);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $dob = htmlspecialchars($dob);
    $phone = htmlspecialchars($phone);
    $street = htmlspecialchars($street);
    $city = htmlspecialchars($city);
    $state = htmlspecialchars($state);
    $acc_date = htmlspecialchars($acc_date);

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Use prepared statements to update user data securely
    $stmt = $conn->prepare("UPDATE users SET
        name=?, username=?, email=?, dob=?, phone=?, street=?, city=?, state=?, acc_date=? 
        WHERE account_num=?");

    $stmt->bind_param("sssssssssss", $name, $username, $email, $dob, $phone, $street, $city, $state, $acc_date, $id);

    if ($stmt->execute()) {
        // Redirect to another page after successful update
        header("Location: userinfo.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Close the database connection
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

            <!-- Address Section -->
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

    <!-- Footer Section -->
    <footer>
        &copy; <?php echo date("Y");?> Artha Sanjal. All rights reserved.
    </footer>

</body>
</html>
