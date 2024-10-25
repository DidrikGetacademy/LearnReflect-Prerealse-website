<?php
header("Access-Control-Allow-Origin: https://www.learnreflects.com");
header("Content-Type: application/json"); // Set response type to JSON
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
$hostname = 'learnreflects.com.mysql'; 
$username = 'learnreflects_comlearnreflects'; 
$password = 'FuckOff123'; 
$database = 'learnreflects_comlearnreflects'; 

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check connection
if ($conn->connect_error) {
    // Log the error
    error_log("Connection failed: " . $conn->connect_error);
    // Respond with an error
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit;
} else {
    // If connection successful, send a success message
    echo json_encode(["message" => "Database connected successfully."]);
}

// Close the connection
$conn->close();
?>
