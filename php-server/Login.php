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



?>