<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arthasanjal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $L_U_id = $_POST['L_U_id'];
    $phones = $_POST['phone'];
    $passwords = $_POST['password'];

    $phone_columns = ['Ph_1', 'Ph_2', 'Ph_3', 'Ph_4', 'Ph_5'];
    $password_columns = ['PassW_1', 'PassW_2', 'PassW_3', 'PassW_4', 'PassW_5'];

    $phone_data = [];
    $password_data = [];

    for ($i = 0; $i < min(count($phones), count($phone_columns)); $i++) {
        if (!empty($phones[$i])) {
            $phone_data[$phone_columns[$i]] = $phones[$i];
        }
    }

    for ($i = 0; $i < min(count($passwords), count($password_columns)); $i++) {
        if (!empty($passwords[$i])) {
            $password_data[$password_columns[$i]] = $passwords[$i];
        }
    }

    $sql = "INSERT INTO lowuserph (L_U_id, Ph_1, Ph_2, Ph_3, Ph_4, Ph_5) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    
    $stmt->bind_param(
        "isssss",
        $L_U_id,
        $phone_data['Ph_1'],
        $phone_data['Ph_2'],
        $phone_data['Ph_3'],
        $phone_data['Ph_4'],
        $phone_data['Ph_5']
    );
    
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO lowuserpassw (L_P_ID, PassW_1, PassW_2, PassW_3, PassW_4, PassW_5) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    
    $stmt->bind_param(
        "isssss",
        $L_U_id,
        $password_data['PassW_1'] ,
        $password_data['PassW_3'] ,
        $password_data['PassW_4'] ,
        $password_data['PassW_5'] ,
        $password_data['PassW_2'] ,
    );
    
    $stmt->execute();
    $stmt->close();

    echo "Data successfully inserted!";
}

$conn->close();
?>
