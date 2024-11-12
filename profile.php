<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Loan</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body class="light-mode">
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <a href="memberpage.php">Home</a>
        <a href="profile.php">My Profile</a>

        <!-- Account Information with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('account-information')">Account Information</a>
        <div class="submenu" id="account-information">
            <a href="savingaccinfo.php">Saving Account Information</a>

            <a href="loanaccinfo.php">Loan Account Information</a>
        </div>

        <!-- Services with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('services')">Services</a>
        <div class="submenu" id="services">
            <a href="requestloan.php">Request Loan</a>
            <a href="reqaccstatement.php">Request Account Statement</a>
        </div>

        <a href="support.php">Support/Help</a>
        <a href="#">Sign Out</a>
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
        <h1>My Profile</h1>

        <!-- Editable Profile Info Section -->
        <div class="subpage">
            <h2>Personal Information</h2>
            <form action="update_profile.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="ram bahadur" required>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="ram123" disabled>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="ramram@example.com" required>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="1990-01-01">

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="+1 234 567 890">

                <button type="submit">Save Changes</button>
            </form>
        </div>

        <!-- Address Section -->
        <div class="subpage">
            <h2>Address</h2>
            <form action="update_address.php" method="POST">
                <label for="street">Street Address:</label>
                <input type="text" id="street" name="street" value="rammarg" required>

                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="ramnagar" required>

                <label for="state">State:</label>
                <input type="text" id="state" name="state" value="aayodhya" required>

                <label for="zip">ZIP Code:</label>
                <input type="text" id="zip" name="zip" value="1111" required>

                <button type="submit">Update Address</button>
            </form>
        </div>

        <!-- Account Status Section -->
        <div class="subpage">
            <h2>Account Status</h2>
            <p><strong>Account Type:</strong> Saving</p>
            <p><strong>Account Status:</strong> Active</p>
            <p><strong>Last Login:</strong> 2024-11-12</p>
        </div>

        <!-- Change Password Section -->
        <div class="subpage">
            <h2>Change Password</h2>
            <form action="change_password.php" method="POST">
                <label for="old-password">Old Password</label>
                <input type="password" id="old-password" name="old-password" required>

                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" required>

                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit">Change Password</button>
            </form>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; <?php echo date("Y");?> Artha Sanjal. All rights reserved.
    </footer>
    
</body>
</html>
