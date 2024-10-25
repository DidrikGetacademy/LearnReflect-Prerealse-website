<?php
header("Access-Control-Allow-Origin: https://www.learnreflects.com");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
//handle preflight-request
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    http_response_code(200);
    exit();   
  }

// Include the database configuration file
include 'config.php';  // Assumes config.php is in the same directory

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create database connection using credentials from config.php
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
}

// SQL query to get the count of emails
$sql = "SELECT COUNT(*) AS count FROM email_registration";
$result = $conn->query($sql);

// Return the count in JSON format
if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(["count" => $row['count']]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to retrieve data"]);
}

$conn->close();
?>
