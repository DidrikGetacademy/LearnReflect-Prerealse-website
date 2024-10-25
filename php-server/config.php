<<<<<<< HEAD
<?php
// PayPal Configuration
$paypalClientId = 'AT8yB3Y26sv1pIjYB-mIMoEwXDRAV5gmIHDLBve8YSvFhPqjCI-aXVzINu9TnqhKErtvbn5IqRwB01';
$paypalClientSecret = 'EJRnHrp2ytIzf_gn3ToapTaVDRwKAasS-VWmXJegOCtmNzEeIu0iwFthlT3PBnSZ7jyap1COydeNw';

// Database Configuration
$hostname = 'learnreflects.com.mysql';
$username = 'learnreflects_comlearnreflectdb';
$password = 'sluttogs123';
$database = 'learnreflects_omlearnreflectdb';
?>
=======
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
>>>>>>> e92031d0 (updated github)
