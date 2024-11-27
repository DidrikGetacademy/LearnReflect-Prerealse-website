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

$data = json_decode(file_get_contents("php://input"),true);

$Userid = $data['id'];
$email = $data['email'];

if(!$Userid || !$email){
    http_response_code(400);
    error_log("Email and password are required");
    echo json_encode(["success" => false,'message' => "UserID and email required"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, name, email, subscription_type, FROM users WHERE id = ? AND email = ?");
if(!$stmt){
    http_response_code(500);
    error_log("Failed to prepare statement: ". $conn->error);
    echo json_encode(['success' => false, 'message' => "Internal server error"]);
    exit;
}

$stmt->bind_param("ss",$Userid,$email);
$stmt->execute();


$result = $stmt->get_result();
if ($result->num_rows > 0){
    $user = $result->fetch_assoc();
    echo json_encode(['success' => true, 'user' => $user]);
}else { 
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => "User not found"]);
}
$stmt->close();
$conn->close();
?>