<?php
include 'config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Conrol-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set('log_errors',1);
ini_set('error_log','error_log.txt');

try {

    
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
    $email = htmlspecialchars(trim($data['email']));
    $name = htmlspecialchars(trim($data['name']));
    $password = htmlspecialchars(trim($data['password']));
    
    
    #Valider e-post    
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        http_response_code(400); //BadRequest
        echo json_encode([
            "success" => false,
            "message" => "Invalid email format."
        ]);
        error_log("Invalid email format");
        exit;
    }
    
    
    
    #Returnerer en 400 feilmelding dersom email,username,passord er tomme.
    if (empty($email) || empty($name) || empty($password)){
        http_response_code(400); //BadRequest internal server error!
        echo json_encode([
            "success" => false,
            "message" => "Empty name,email or password recived"
        ]);
        error_log("Email, username or password is empty");
        exit;
    }
    
    
    
    #sjekker om epost allerede eksisterer i databasen.
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows > 0){
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Email already registred"
        ]);
        error_log("Email already registred");
        exit;
    }
    $stmt-> close();
    
    
    
    #hash password
    $hashed_password = password_hash($password,PASSWORD_BCRYPT);
    
    
    #Insert into database
    $stmt = $conn->prepare("INSERT INTO users (name,email,password, subscription_type, created_at) VALUES (?, ?, ?, 'none', NOW())");
    $stmt->bind_param("sss",$name,$email,$hashed_password);
    
    if($stmt->execute()){
        http_response_code(201); //Success response
        echo json_encode([
            "success" => true,
            "message" => "success"
        ]);
        error_log("Success! email registred in database successfully.");
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to register user."]);
        error_log("Error executing INSERT: " . $stmt->error);
        exit;
    }
    $stmt->close();
} finally{
    if($conn){
        $conn->close();
    };
}
    ?>