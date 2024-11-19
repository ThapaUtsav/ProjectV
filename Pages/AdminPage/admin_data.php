<?php
session_start();
$admin_account_number = isset($_SESSION['acc_no']) ? $_SESSION['acc_no'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Data Entry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('logo.png') no-repeat center center;
            background-size: contain;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: rgb(196, 193, 193);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: black;
            margin-bottom: 20px;
        }
        .input-block {
            margin-bottom: 15px;
        }
        label {
            font-size: 14px;
            color: #000000;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        .form-actions {
            text-align: center;
        }
        .form-actions input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }
        .form-actions input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h1>Admin Data Entry</h1>
        <form action="admin_data_insert.php" method="POST">
            <div class="input-block">
                <label for="admin-name">Name</label>
                <input type="text" id="admin-name" name="admin_name" required>
            </div>
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
    
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob">

            <label for="street">Street Address:</label>
            <input type="text" id="street" name="street" required>
    
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
    
            <label for="state">State:</label>
            <input type="text" id="state" name="state" required>

            <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($admin_account_number); ?>">

            <div class="form-actions">
                <input type="submit" value="Submit Data">
            </div>
        </form>
        <footer>
            &copy; <?php echo date("Y");?> Artha Sanjal. All rights reserved.
        </footer>
    </div>

</body>
</html>
