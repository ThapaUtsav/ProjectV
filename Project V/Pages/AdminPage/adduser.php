<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
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
    <form action="manageuser.php" method="POST">
    <div class="subpage">
        <h1>Create New User Profile</h1>

        <!-- Personal Information Section -->
        <h2>Personal Information</h2>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob">

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone">

        <!-- Address Section -->
        <h2>Address</h2>
        <label for="street">Street Address:</label>
        <input type="text" id="street" name="street" required>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>

        <label for="state">State:</label>
        <input type="text" id="state" name="state" required>

        <label for="zip">ZIP Code:</label>
        <input type="text" id="zip" name="zip" required>

        <!-- Account Status Section -->
        <h2>Account Status</h2>
        <label for="accountType">Account Type:</label><br>
        <select name="accountType" id="accountType">
            <option value="saving">Saving</option>
            <option value="loan">Loan</option>
        </select><br>

        <label for="accDate">Account Created On:</label>
        <input type="date" id="accDate" name="accDate">

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
