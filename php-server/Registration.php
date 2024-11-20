<?php
include 'config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Conrol-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set('log_errors',1);
ini_set('error_log','error_log.txt');

$data = json_decode(file_get_contents('php://input'),true);

$conn = new mysqli($hostname,$username,$password,$database);

if($conn->connect_error){
    error_log("Database Connection failed");
    die(json_encode(["success" => false, "message" => "Database Connection Failed"]));
}

#Returnerer en 400 feilmelding dersom Email & Password mangler i forespørselen.
if(!isset($data['email']) ||  !isset($data['name']) || !isset($data['password']) ){
    http_response_code(400);//BadRequest
    echo json_encode([
        "Success" => false,
        "message" => "Username , Email or password is missing."
    ]);
    error_log("Missing email,name,password inn request too php");
    exit;
}


#Get data from request, rens input data
$Email = htmlspecialchars(trim($data['email']));
$name = htmlspecialchars(trim($data['name']));
$Password = htmlspecialchars(trim($data['password']));


#Valider e-post    
if(!filter_var($Email,FILTER_VALIDATE_EMAIL)){
    http_response_code(400); //BadRequest
    echo json_encode([
        "success" => false,
        "message" => "Invalid email format."
    ]);
    error_log("Not a valid email");
    exit;
}



#Returnerer en 400 feilmelding dersom email,username,passord er tomme.
if (empty($Email) || empty($name) || empty($password)){
    http_response_code(500); //BadRequest internal server error!
    error_log("Email, username or password is empty");
    die(json_encode(["success" => false, "message" => "Internal server ERROR"]));
}



#sjekker om epost allerede eksisterer i databasen.
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s",$Email);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Email already registred"
    ]);
    error_log("Email already registred");
    exit();
}
$stmt-> close();



#hash password
$hashed_password = password_hash($password,PASSWORD_BCRYPT);


#Insert into database
$stmt = $conn->prepare("INSERT INTO users (name,name,password, subscription_type, created_at) VALUES (?, ?, ?, 'none', NOW())");
$stmt->bind_param("sss",$name,$Email,$hashed_password);

if($stmt->execute()){
    http_response_code(201); //Success response
    echo json_encode([
        "success" => true,
        "message" => "User registred successfully"
    ]);
    error_log("Success! email registred in database successfully.");
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to register user."]);
    error_log("Error executing INSERT: " . $stmt->error);
}
$stmt->close();
$conn->close();
?>