<?php
include 'config.php';
include 'error_log.txt';
include 'error_log.php';

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', 'error_log.txt'); // Point to the correct log file


$data = json_decode(file_get_contents('php://input'), true);
$key_code = $data["key_code"];

$conn = new mysqli($hostname, $username, $password, $database); //connection to database with data from config

if($conn->connect_error)
{
    die(json_encode(["success" => false, "error" => "Database connection failed"]));
}

$stmt = $conn->prepare("SELECT * FROM activation_keys WHERE key_code = ? AND status = 'unused'");
$stmt->bind_param("s", $key_code);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    // oppdaterer nøkkel status til aktiv vis den er gyldig.
    $update_stmt = $conn->prepare("UPDATE activation_keys SET status = 'active', activated_at = NOW() WHERE key_code = ?");
    $update_stmt->bind_param("s",$key_code);
    $update_stmt->execute();
    $update_stmt->close();

    echo json_encode(["success" => true]);
}else {
    echo json_encode(["success" => false,"error"=> "Invalid or already used key"]);
}

$stmt->close();
$conn->close();
?>