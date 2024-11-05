<?php
#Script to generate activasionkey after payment success.
#1. checks that the payment amount is correct.
#2. if the payment is verified it then generates a unique activasion key
#3. sends the activasion key too the client/frontend.

#NB, lager nøkkel og sender til klient ved godkjent betaling.



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
$amount = $data['amount'];
$email = $data['email'];

// sjekker at amount er korrekt verdi
if ($amount == "20.00"){
$key_code = strtoupper(bin2hex(random_bytes(8))); // nøkkel med 8 random nummer lengde.
$expires_at = date("Y-m-d H:i:s", strtotime("30+ days")); //utgår etter 30 dager



 //lagre nøkkel i databasen.
 $conn = new mysqli($hostname, $username, $password, $database); //connection to database with data from config


// connection Error reporting
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error); 
}    


// Sql detaljer som blir kjørt.
$stmt = $conn->prepare("INSERT INTO activation_keys (key_code, status, created_at, expires_at, usage_limit, times_used) VALUES (?, 'unused', NOW(), ?, 1, 0)");

// binder parametere til den forespurte sql injectionnen.
$stmt->bind_param("ss", $key_code, $expires_at);



// kjører forhåndsbestemt  database foresøprsel 
if($stmt->execute()){
    echo json_encode(["key_code" => $key_code]); //returnerer nøkkel ved sukksess til frontend/client som forespør. 
}else {
    echo json_encode(["error" => "Failed to generate key"]); // returnerer error vis nøkkel ikke ble generert
}

$stmt->close();
$conn->close();
} else {
    echo json_encode(["Error" => "Invalid payment amount"]); // hvis betalingsverdi er feil.
}






?>