<?php
include 'config.php';


header("Access-Control-Allow-Origin: *");
header("Access-Conrol-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set('log_errors',1);
ini_set('error_log','error_log.txt');


$conn = new mysqli($hostname, $username, $password, $database);

if($conn->connect_error){
    echo json_encode(["success" => false, "message" => "Failed to connect too database"]);
    error_log("Database connection failed");
}

$data = json_decode(file_get_contents("php://input"));

if(!$email || $password){

    http_response_code(400);//bad request
    echo json_encode(["message" => "Email and password are reqired"]);
    exit;
} 

$stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
$stmt->bind_param("s",$email);
$stmt->execute();
$stmt->bind_result($hashed_password);

if($stmt->fetch() && password_verify($password,$hashed_password)){
    http_response_code(200);
    echo json_encode(["message" => "Login successful."]);
    error_log("Successful login");
}else {
    http_response_code(401);
    echo json_encode(["message" => "Invalid credentials"]);
    error_log("Invalid Credentials");
}
$stmt->close();
?>