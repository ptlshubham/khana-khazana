<?php
// Configuration
$email_it_to = "your_own_email_address@some_domain.com";
$error_message = "Please complete the form first";

// Retrieving form data
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$body = $_POST['body'];

// Database connection
$servername = "localhost"; // Change if needed
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "khana-khazana"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($body)) {
        echo "<script>alert('Message cannot be empty!');window.location.href='../contact.html';</script>";
    } else {
        // Prepare SQL query
        $sql = "INSERT INTO contact (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $subject, $body);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Message Sent Successfully!'); window.location.href='../contact.html';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');window.location.href='../contact.html';</script>";
        }

        // Close connections
        $stmt->close();
    }
    $conn->close();
}

// Prepare email
$email_from = $email;
$email_message = "Message submitted by '".stripslashes($name)."', email:".$email_from;
$email_message .=" on ".date("d/m/Y")."\n\n";
$email_message .= stripslashes($body);
$email_message .="\n\n";

// Send email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8". "\r\n";
$headers .= 'From: '.stripslashes($name);

mail($email_it_to, $subject, $email_message, $headers);
?>
