<?php
header("Access-Control-Allow-Origin: https://www.learnreflects.com");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");


include 'config.php';  
include 'error_log.txt';
include 'error_log.php';



error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1); 
ini_set('error_log', 'error_log.txt'); 


$conn = new mysqli($hostname, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
 
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->email)) {
        $email = $conn->real_escape_string($data->email);

 
        $checkEmailQuery = "SELECT * FROM email_registration WHERE email = '$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {

            http_response_code(409); 
            echo json_encode(["error" => "Email is already registered."]);
        } else {
         
            $sql = "INSERT INTO email_registration (email) VALUES ('$email')";

            if ($conn->query($sql) === TRUE) {
                http_response_code(200);
                echo json_encode(["message" => "Valid"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to save email: " . $conn->error]);
            }
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Email is required"]);
    }
}

$conn->close();
?>
