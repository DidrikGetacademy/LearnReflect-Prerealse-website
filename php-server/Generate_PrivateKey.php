<?php

 $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

 $Allowed_origins = [
       'https://www.learnreflects.com',
       'https://www.learnreflects.com/Payment',
       'https://api.sandbox.paypal.com/v1/oauth2/token',
       'https://api.sandbox.paypal.com/v2/checkout/orders/',
 ];

  if (in_array($origin, $Allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
  }else {
    header("Access-Control-Allow-Origin: 'none'");
  }
// Headers for CORS and JSON response
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD' == 'OPTIONS']){
    exit(0);
}
// Include necessary files
include 'config.php';
require 'vendor/autoload.php';

// Error reporting
error_reporting(E_ALL);  // Disable error reporting
ini_set('memory_limit', '2048M');
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');
ini_set('display_errors', 0);

try {
    // Paypal Credentials
    $paypal_url = 'https://api.sandbox.paypal.com/v1/oauth2/token';
    $client_id = $ClientID;
    $client_secret = $secretID;
    $headers = array('Accept: application/json', 'Content-Type: application/x-www-form-urlencoded');
    $data = 'grant_type=client_credentials';

    // cURL request to get access token
    $ch = curl_init($paypal_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $client_secret);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        #ERROR LOG
        error_log("Curl error: " . curl_error($ch));
        echo json_encode(["error" => "Failed to retrieve PayPal access token."]);
        exit;
    }

    $response_data = json_decode($response);
    curl_close($ch);

    if (!isset($response_data->access_token)) {
        #ERROR LOG
        error_log("Error retrieving PayPal access token: " . $response);
        echo json_encode(["error" => "Unable to retrieve PayPal access token."]);
        exit;
    }

    $access_token = $response_data->access_token;

    #ERROR LOG
    error_log("Access Token: " . $access_token);

    // Decode input data
    $data = json_decode(file_get_contents('php://input'), true);

    if(json_last_error() != JSON_ERROR_NONE){
        error_log('JSON Decode Error: ' . json_last_error_msg());
        echo json_encode(['error' => "Invalid JSON input."]);
        exit;
    }

    $amount = $data['amount'] ?? null;
    $email = $data['email'] ?? null;
    $order_id = $data['order_id'] ?? null;

    #ERROR LOG
    error_log("Received data: Amount = " . $amount . ", Email = " . $email . ", Order ID = " . $order_id);

    // Validate order ID
    if (!$order_id) {
        error_log("Missing order ID.");
        echo json_encode(["error" => "Missing order ID"]);
        exit;
    }

    // Validate amount and email
    if (!$amount || !$email) {
        #ERROR LOG
        error_log("Missing amount or email.");
        echo json_encode(["error" => "Amount or email is missing"]);
        exit;
    }

    // Get order details from PayPal API
    $paypal_url = 'https://api.sandbox.paypal.com/v2/checkout/orders/' . $order_id;
    $headers = array('Authorization: Bearer ' . $access_token);

    $ch = curl_init($paypal_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        #ERROR LOG
        error_log("Curl error fetching order details: " . curl_error($ch));
        echo json_encode(["error" => "Failed to retrieve order details."]);
        exit;
    }

    $order_details = json_decode($response);
    curl_close($ch);
    #ERROR LOG
    error_log("Order details: " . print_r($order_details, true));

    $purchase_amount = "20.00";
    #ERROR LOG
    error_log("Expected amount: $purchase_amount, received: " . $order_details->purchase_units[0]->amount->value);

    // Validate payment
    if (isset($order_details->status) && $order_details->status == 'COMPLETED' && isset($order_details->purchase_units[0]->amount->value) && $order_details->purchase_units[0]->amount->value == $purchase_amount) {

        $key_code = strtoupper(bin2hex(random_bytes(8))); 
        #ERROR LOG
        error_log("Generated Key Code NOT YET sent too frontend: " . $key_code);

        // Database connection
        $conn = new mysqli($hostname, $username, $password, $database);
        if ($conn->connect_error) {
            #ERROR LOG
            error_log("Database connection failed: " . $conn->connect_error);
            echo json_encode(["error" => "Database connection failed"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO activation_keys (key_code, status, created_at, usage_limit, times_used) VALUES (?, 'unused', NOW(), 1, 0)");
        $stmt->bind_param("s", $key_code);

        if ($stmt->execute()) {
            // Respond with only the key code
            #ERROR LOG
            error_log('Key_code inserted into the database table: ' . $key_code);
            echo json_encode(['key_code' => $key_code]);
            #ERROR LOG
            error_log('Response sent to frontend: Key code generated' . $key_code);
            exit;
        } else {
            // Log the error and send a failure response
            #ERROR LOG
            error_log("Failed to insert key into the database.");
            echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
            exit;
        }

    } else {
        #ERROR LOG
        error_log("Payment verification failed or amount mismatch");
        echo json_encode(["error" => "Payment verification failed or amount mismatch"]);
        exit;
    }

} catch (Exception $e) {
    #ERROR LOG
    error_log("Unhandled Exception: " . $e->getMessage());
    echo json_encode(["error" => "An unexpected error occurred."]);
    exit;
} finally {
    // Clean up the statement and connection
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>
