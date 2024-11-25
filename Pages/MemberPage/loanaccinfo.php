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
            <a href="../../userfinance/index.php">Deposit </a>
            <a href="../../userfinance/loanindex.php">Loan </a>
        </div>
    
        <!-- Services with Submenu -->
        <a href="javascript:void(0);" onclick="toggleSubmenu('services')">Services</a>
        <div class="submenu" id="services">
            <a href="reqaccstatement.php">Loan Repayment</a>
            <a href="DepHist.php">Deposit History</a>
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
        <h1>Loan Account Information</h1>
        <div class="subpage">
            <p><strong>Loan Account Number:</strong> 987654321</p>
            <p><strong>Loan Holder Name:</strong> ram bahadur</p>
            <p><strong>Loan Amount:</strong> Rs15,000</p>
            <p><strong>Interest Rate:</strong> 5%</p>
            <p><strong>Loan Start Date:</strong> January 1, 2023</p>
            <p><strong>Loan Term:</strong> 36 months</p>
            <p><strong>Outstanding Balance:</strong> Rs12,000</p>
        </div>

        <!-- Repayment History Section -->
        <div class="repayment-history">
            <h2>Repayment History</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Amount Paid</th>
                    <th>Outstanding Balance</th>
                </tr>
                <tr>
                    <td>2024-10-01</td>
                    <td>Rs500</td>
                    <td>Rs11,500</td>
                </tr>
                <tr>
                    <td>2024-09-01</td>
                    <td>Rs500</td>
                    <td>Rs12,000</td>
                </tr>
                <!-- Add more rows as needed -->
            </table>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; <?php echo date("Y");?> Artha Sanjal. All rights reserved.
    </footer>
    
</body>
</html>
