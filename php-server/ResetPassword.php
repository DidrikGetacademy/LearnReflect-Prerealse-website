<?php

#Database Credentials
include 'config.php';


#Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: content-type");


#ERROR logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', 'error_log.txt'); // Point to the correct log file


#Database connection
$conn = new mysqli($hostname,$username,$password,$database);


#Error connection
if($conn->connect_error){
    echo json_encode(["success" => false, "message" => "Failed to connect to database"]);
    error_log("Failed too connect to database");
    exit;
}

$data = json_decode(file_get_contents("php://input"));

$email = $data['email'] ?? null;

if(!$email){
    http_response_code(400);
    echo json_encode(["Message" => "Email is required"]);
    error_log("Email is missing");
    exit;
}


$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $user = $result->fetch_assoc();
    $Userid = $user['id'];


    #Generating a reset token
    $resetToken = bin2hex(random_bytes(16));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    #save the token in database
    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, reset_token, expiry) VALUES (?,?,?) ON DUPLICATE KEY UPDATE reset_token=?, expiry=?");
    $stmt->bind_param("issss",$Userid,$resetToken,$expiry,$resetToken,$expiry);
    $stmt->execute();

    #mail sending logic
    sendEmailResetPasswordToken($email, $resetToken);
    error_log("Sent email with reset token");
    echo json_encode(["success" => true, "message" => "Reset code sent to you email"]);

}else {
    error_log("Email not found");
    http_response_code(404);
    echo json_encode(["message" => "Email not found"]);
}
$stmt->close();
$conn->close();
?>