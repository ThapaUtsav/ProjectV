<?php

// Database connection parameters
$host = 'localhost';
$dbname = 'arthasanjal';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name FROM test";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'><tr><th>Name</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['name'] . "</td></tr>";
    }

    echo "</table>";
} else {
    echo "No results found.";
}

$conn->close();
?>
