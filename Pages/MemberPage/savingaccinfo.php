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
        <!-- Home and Profile -->
        <a href="memberpage.html">Home</a>
        <a href="profile.php">My Profile</a>
    
        <!-- Account Information with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('account-information')">Account Information</a>
        <div class="submenu" id="account-information">
            <a href="../../userfinance/index.php">Deposit Amount</a>
            <a href="../../userfinance/loanindex.php">Loan Amount</a>
        </div>
    
        <!-- Services with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('services')">Services</a>
        <div class="submenu" id="services">
            <a href="reqaccstatement.php">Loan Repayment</a>
        </div>
    
        <!-- Support and Sign Out -->
        <a href="support.php">Support/Help</a>
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
        <h1>Saving Account Information</h1>
        <div class="subpage">
            <p><strong>Account Number:</strong> 123456789</p>
            <p><strong>Account Holder Name:</strong> ram bahadur</p>
            <p><strong>Account Balance:</strong>  Rs10,000</p>
            <p><strong>Interest Rate:</strong> 4%</p>
            <p><strong>Account Opening Date:</strong> January 1, 2020</p>
        </div>

        <!-- Transaction History Section -->
        <div class="transaction-history">
            <h2>Transaction History</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Balance</th>
                </tr>
                <tr>
                    <td>2024-10-01</td>
                    <td>Deposit</td>
                    <td>Rs500</td>
                    <td>Rs10,500</td>
                </tr>
                <tr>
                    <td>2024-09-15</td>
                    <td>Withdrawal</td>
                    <td>Rs200</td>
                    <td>Rs10,300</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; <?php echo date("Y");?> Artha Sanjal. All rights reserved.
    </footer>
    
</body>
</html>
