<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Loan</title>
    <link rel="stylesheet" href="adminstyle.css">
    <script src="script.js"></script>
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
        <h1>Support/Help</h1>

        <!-- FAQ Section -->
        <div class="subpage">
            <h2>Frequently Asked Questions (FAQ)</h2>
            <div class="subpage">
                <h3>How can I update my profile information?</h3>
                <p>You can update your profile information by navigating to the "My Profile" section, where you can edit your details and save changes.</p>
            </div>
            <div class="subpage">
                <h3>How do I reset my password?</h3>
                <p>Go to the "My Profile" section and select the "Change Password" option. Enter your current password and your new password, and then save.</p>
            </div>
            <div class="subpage">
                <h3>How can I request account statements?</h3>
                <p>Navigate to the "Services" section and choose "Request Account Statement" to receive your account statements via email or download.</p>
            </div>
        </div>

        <!-- Support Contact Form -->
        <div class="support-form-section">
            <h2>Contact Support</h2>
            <form action="submit_support_request.php" method="POST">
                <label for="support-name">Name:</label>
                <input type="text" id="support-name" name="name" required>

                <label for="support-email">Email:</label>
                <input type="email" id="support-email" name="email" required>
                <label for="support-query">Your Query:</label>
                <textarea id="support-query" name="query" rows="5" placeholder="Describe your issue here..." required></textarea>

                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Contact Information -->
        <div class="contact-info-section">
            <h2>Customer Service</h2>
            <p>If you need further assistance, please contact our customer service team:</p>
            <p><strong>Email:</strong> support@example.com</p>
            <p><strong>Phone:</strong> +1 (123) 456-7890</p>
            <p><strong>Hours:</strong> Monday to Friday, 9:00 AM - 5:00 PM</p>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; <?php echo date("Y");?> Artha Sanjal. All rights reserved.
    </footer>
    
</body>
</html>
