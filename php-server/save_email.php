<?php
header("Access-Control-Allow-Origin: https://www.learnreflects.com");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


$hostname = "learnreflects.com.mysql:3306";
$username = "learnreflects_comlearnreflectdb";
$password = "*********"; 
$database = "learnreflects_comlearnreflectdb";


error_reporting(E_ALL);
ini_set('display_errors', 1);


$conn = new mysqli($hostname, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->email)) {
        $email = $conn->real_escape_string($data->email);


        $sql = "INSERT INTO email_registration (email) VALUES ('$email')";

        if ($conn->query($sql) === TRUE) {
            http_response_code(200);
            echo json_encode(["message" => "Email saved successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to save email: " . $conn->error]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Email is required"]);
    }
}

$conn->close();
?>
