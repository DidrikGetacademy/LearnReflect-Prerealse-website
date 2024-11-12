<?php
// This script handles sending the email asynchronously
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');
ini_set('display_errors', 0);

// Function to send the email
function sendEmail($email, $key_code) {
    // Email content
    $subject = "Your Activation Key";
    $message = "Hello,\n\nYour activation key is: $key_code\n\nThank you for your purchase!";
    $headers = "From: no-reply@learnreflects.com";

    // Attempt to send the email
    if (mail($email, $subject, $message, $headers)) {
        error_log("Email sent successfully to $email");
    } else {
        error_log("Failed to send email to $email");
    }
}
?>
