<?php

#database
include 'config.php';

#headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

#Error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', 'error_log.txt'); // Point to the correct log file

#connecting too database
$conn = new mysqli($hostname,$username,$password,$database);

if($conn->connect_error){
    echo json_encode(["success" => false, "message" => "Failed to connect to database"]);
    error_log("Connection to database failed");
    exit;
}

$data = json_decode(file_get_contents("php://input"),true);

$token = $data['token'] ?? null;
$newpassword = $data['password'] ?? null;

if(!$token || !$newpassword){
    http_response_code(400);
    echo json_encode(["message" => "token and new password are required"]);
    error_log("Token and new password are required");
    exit;
}

#token validation
$stmt = $conn->prepare("SELECT user_id, expiry FROM password_resets WHERE reset_token = ?");
$stmt->bind_param("s",$token);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $reset = $result->fetch_assoc();

    if (strtotime($reset['exipry']) < time()){
        echo json_encode(["message" => "Reset token expired"]);
        error_log("Reset token expired");
        exit;
    }

    #updating password
    $hashed_password = password_hash($newpassword,PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users set password = ? WHERE id = ?");
    $stmt->bind_param("si",$hashed_password,$reset['userid']);
    $stmt->execute();


    #deleting token
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE reset_token = ?");
    $stmt->bind_param("s",$token);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Password updated succssfully"]);
    error_log("Password updated successfully");
} else {
    http_response_code( 400);
    error_log("Invalid reset token");
    echo json_encode(["message" => "Invalid reset token"]);
 
}
$stmt->close();
$conn->close();

?>