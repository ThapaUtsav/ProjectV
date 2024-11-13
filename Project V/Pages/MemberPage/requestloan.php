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
        <h1>Request Loan</h1>
        <form class="subpage" id="request-loan">
            <label for="loan-amount">Loan Amount:</label>
            <input type="number" id="loan-amount" name="loan-amount" min="1000" placeholder="Enter amount" required>

            <label for="loan-purpose">Purpose of Loan:</label>
            <input type="text" id="loan-purpose" name="loan-purpose" placeholder="Enter purpose" required>
            
            <label for="loan-term">Loan Term (in months):</label>
            <input type="number" id="loan-term" name="loan-term" min="6" max="60" placeholder="Enter term in months" required>

            <label for="interest-rate">Interest Rate (%):</label>
            <input type="number" id="interest-rate" name="interest-rate" value="5" readonly>

            <button type="submit">Submit Loan Request</button>
        </form>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; <?php echo date("Y");?> Artha Sanjal. All rights reserved.
    </footer>
    
</body>
</html>
