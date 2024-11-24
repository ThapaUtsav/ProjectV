<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arthasanjal";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = "";
$successMessage = "";

// Check if the form was submitted to approve or reject a loan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loanId = $_POST['loan_id'];
    $action = $_POST['action'];  // approve or reject
    $accountNum = $_SESSION['userID'];
    $rejectionReason = isset($_POST['rejection_reason']) ? $_POST['rejection_reason'] : '';

    // Update the loan status based on approval/rejection
    if ($action == 'approve') {
        $sql = "UPDATE loans SET loan_status = 'approved', approved_by = ? WHERE loan_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $accountNum, $loanId);
        if ($stmt->execute()) {
            $successMessage = "Loan approved successfully.";
        } else {
            $errorMessage = "Error approving loan: " . $stmt->error;
        }
    } elseif ($action == 'reject') {
        $sql = "UPDATE loans SET loan_status = 'rejected', approved_by = ?, rejection_reason = ? WHERE loan_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $accountNum, $rejectionReason, $loanId);
        if ($stmt->execute()) {
            $successMessage = "Loan rejected successfully.";
        } else {
            $errorMessage = "Error rejecting loan: " . $stmt->error;
        }
    }
}

// Fetch pending loan requests for approval/rejection
$sql = "SELECT * FROM loans WHERE loan_status = 'pending'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Approval</title>
</head>
<body>
    <h2>Loan Approval/Rejection</h2>

    <?php if ($successMessage): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Loan ID</th>
                <th>Account Number</th>
                <th>Loan Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['loan_id']; ?></td>
                        <td><?php echo $row['account_num']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['loan_status']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="loan_id" value="<?php echo $row['loan_id']; ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit">Approve</button>
                            </form>

                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="loan_id" value="<?php echo $row['loan_id']; ?>">
                                <input type="hidden" name="action" value="reject">
                                <textarea name="rejection_reason" placeholder="Reason for rejection" required></textarea>
                                <button type="submit">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No pending loans.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
