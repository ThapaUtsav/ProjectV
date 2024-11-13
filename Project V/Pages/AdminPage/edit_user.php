<?php
// Database connection
$servername = "localhost";
$username = "root"; // adjust as needed
$password = ""; // adjust as needed
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
</head>
<body>
<h1>Edit User Information</h1>
<form action="edit_user.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
    <label>Username:</label>
    <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
    <label>Date of Birth:</label>
    <input type="date" name="dob" value="<?php echo $user['dob']; ?>"><br>
    <label>Phone:</label>
    <input type="tel" name="phone" value="<?php echo $user['phone']; ?>"><br>
    <label>Street:</label>
    <input type="text" name="street" value="<?php echo $user['street']; ?>" required><br>
    <label>City:</label>
    <input type="text" name="city" value="<?php echo $user['city']; ?>" required><br>
    <label>State:</label>
    <input type="text" name="state" value="<?php echo $user['state']; ?>" required><br>
    <label>ZIP:</label>
    <input type="text" name="zip" value="<?php echo $user['zip']; ?>" required><br>
    <label>Account Type:</label>
    <select name="accountType">
        <option value="saving" <?php if ($user['accountType'] == 'saving') echo 'selected'; ?>>Saving</option>
        <option value="loan" <?php if ($user['accountType'] == 'loan') echo 'selected'; ?>>Loan</option>
    </select><br>
    <label>Account Created On:</label>
    <input type="date" name="accDate" value="<?php echo $user['accDate']; ?>"><br><br>
    <button type="submit">Update User</button>
</form>
</body>
</html>
