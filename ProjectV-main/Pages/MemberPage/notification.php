<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    die("Session expired or user not logged in. Please log in again.");
}

$user_account_number = $_SESSION['userID']; // Get the user account number from the session

// Database connection
$servername = "localhost";
$dbusername = "root";  
$password = "";
$dbname = "arthasanjal";

// Create connection
$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch loans without duplicates
$sql = "
    SELECT DISTINCT loans.loan_id, loans.loan_amount, loans.loan_date, loans.status, loans.payment_method, loans.created_by
    FROM loans 
    JOIN users ON loans.account_number = users.account_num 
    WHERE loans.account_number = ? 
    AND loans.status != 'Paid'  -- Exclude loans that are 'Paid'
    ORDER BY loans.loan_date DESC
";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Check if the prepare failed
if ($stmt === false) {
    die("SQL Prepare Error: " . $conn->error);
}

// Bind parameters (account_number)
$stmt->bind_param("s", $user_account_number);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch the loan records
$loans = [];
while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Notifications</title>
    <link rel="stylesheet" href="style.css">
    <script src="memscript.js" defer></script>
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
            &#9776; <!-- Hamburger icon -->
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
    
    <main>
        <div class="container">
            <h2>Loan Notifications</h2>
            
            <?php if (empty($loans)): ?>
                <p>You have no loan for query at the  moment.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Requested By (Username)</th>
                            <th>Loan Amount</th>
                            <th>Loan Date</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loans as $loan): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($loan['created_by']); ?></td>
                                <td><?php echo htmlspecialchars($loan['loan_amount']); ?></td>
                                <td><?php echo htmlspecialchars($loan['loan_date']); ?></td>
                                <td>
                                    <?php
                                    if ($loan['status'] == 'Pending') {
                                        echo "<span class='status-pending'>Pending</span>"; 
                                    } elseif ($loan['status'] == 'Approved') {
                                        echo "<span class='status-approved'>Approved</span>"; 
                                    } elseif ($loan['status'] == 'Rejected') {
                                        echo "<span class='status-rejected'>Rejected</span>"; 
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($loan['payment_method']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
