<?php
ob_start();
include 'config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', 'error_log.txt'); // Point to the correct log file

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['key_code'])) {
    echo json_encode(["success" => false, "error" => "Missing key_code"]);
    error_log("Error missing key_code");
    ob_end_flush();
    exit();
}
$key_code = $data["key_code"];

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Database connection failed"]));
}


$stmt = $conn->prepare("SELECT * FROM activation_keys WHERE key_code = ? AND status = 'unused'");
$stmt->bind_param("s", $key_code);
$stmt->execute();
$result = $stmt->get_result();



if ($result->num_rows > 0) {
    $key = $result->fetch_assoc();

    if ($key['usage_limit'] <= $key['times_used']) {
        echo json_encode(["success" => false, "error" => "Usage limit exceeded"]);
        error_log("Usage limit exceeded");
        $stmt->close();
        $conn->close();
        return;
    }

    //Update the key's status to 'active' and increment the 'times_used' counter
    $update_stmt = $conn->prepare("UPDATE activation_keys SET status = 'active', activated_at = NOW(), times_used = times_used + 1 WHERE key_code = ?");
    $update_stmt->bind_param("s", $key_code);
    error_log("Activated the key and set as active/used");
    $update_stmt->execute();
    $update_stmt->close();
    echo json_encode(["success" => true]);

    error_log("Activated the key and set as active/used");
    error_log("Success");
    ob_end_flush();
    exit();
} else {
    echo json_encode(["success" => false, "error" => "Invalid or already used key"]);
    error_log("Invalid or already used key!");
    ob_end_flush();
    exit();
}



$stmt->close();
$conn->close();
ob_end_flush();
