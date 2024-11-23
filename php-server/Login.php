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

$email = $data['email'] ?? null;
$password = $data['password'] ?? null;
 
if(!$email || !$password){
    http_response_code(400);//bad request
    error_log("Email and password are required");
    echo json_encode(["message" => "Email and password are reqired"]);
    exit;
} 

$stmt = $conn->prepare("SELECT id, name, email, subscription_type, password FROM users WHERE email = ?");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $user =  $result->fetch_assoc(); // henter brukerdata
    
    if(password_verify($password,$user['password'])){
        http_response_code(200);
        echo json_encode([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'subscription_type' => $user['subscription_type'],
            'message" => "Success'
        ]);
        error_log("Successful login");
        
    }else {
        http_response_code(401); // unautorizhed
        echo json_encode(["message" => "Invalid credentials"]);
        error_log("Invalid Credentials for email: " . $email);
    }
}else {
    http_response_code(401); // unautorizhed
    echo json_encode(["message" => "Invalid credentials"]);
    error_log("Invalid Credentials for email: ". $email);
}
$stmt->close();
$conn->close();
?>