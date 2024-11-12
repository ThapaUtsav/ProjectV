<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $query = htmlspecialchars(trim($_POST['query']));

    // Simple validation
    if (empty($name) || empty($email) || empty($query)) {
        echo "<h2>Error: All fields are required.</h2>";
        echo "<p>Please go back and complete all fields.</p>";
        exit;
    }

    // connect to a database or send an email
    $message = "Support Request Received:\n\n";
    $message .= "Name: $name\n";
    $message .= "Email: $email\n";
    $message .= "Query:\n$query\n";

    // Display a success message to the user
    echo "<h2>Thank you for reaching out, $name!</h2>";
    echo "<p>Your support request has been received. We will contact you at <strong>$email</strong> as soon as possible.</p>";
    echo "<p>Your message:</p>";
    echo "<pre>$query</pre>";


}
?>
