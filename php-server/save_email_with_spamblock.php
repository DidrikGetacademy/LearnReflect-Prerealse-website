x|<?php

    header("Access-Control-Allow-Origin: https://www.learnreflects.com");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");


    include 'config.php';
    include 'error_log.txt';
    include 'error_log.php';


    error_reporting(E_ALL);
    ini_set('log_errors', 1); 
    ini_set('error_log', 'error_log.txt');
    ini_set('display_errors', 0);


    $conn = new mysqli($hostname, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error); 
        http_response_code(500); 
        echo json_encode(["error" => "Database connection failed."]);
        exit;
    }

    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Get user's IP address
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $max_requests = 5; 
        $time_limit = 60; 

 
        $ip_check_query = "SELECT * FROM email_registration_ip WHERE ip_address = '$ip_address'";
        $ip_result = $conn->query($ip_check_query);

        if ($ip_result->num_rows > 0) {
            $ip_data = $ip_result->fetch_assoc();

         
            $last_request_time = strtotime($ip_data['last_request']);
            $time_diff = time() - $last_request_time;

            if ($time_diff < $time_limit) {

                if ($ip_data['request_count'] >= $max_requests) {
                    http_response_code(429);
                    echo json_encode(["error" => "Oops! Please try again later."]);
                    exit;
                } else {
            
                    $update_query = "UPDATE email_registration_ip SET request_count = request_count + 1, last_request = NOW() WHERE ip_address = '$ip_address'";
                    $conn->query($update_query);
                }
            } else {
             
                $update_query = "UPDATE email_registration_ip SET request_count = 1, last_request = NOW() WHERE ip_address = '$ip_address'";
                $conn->query($update_query);
            }
        } else {
          
            $insert_query = "INSERT INTO email_registration_ip (ip_address, request_count) VALUES ('$ip_address', 1)";
            $conn->query($insert_query);
        }

        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->email)) {
            $email = $conn->real_escape_string($data->email);

      
            $checkEmailQuery = "SELECT * FROM email_registration WHERE email = '$email'";
            $result = $conn->query($checkEmailQuery);

            if ($result->num_rows > 0) {
   
                echo json_encode(["error" => "Email already registered."]);
            } else {
      
                $sql = "INSERT INTO email_registration (email) VALUES ('$email')";


                if ($conn->query($sql) === TRUE) {
                    http_response_code(200);
                    echo json_encode(["message" => "Valid"]);
                } else {
              
                    error_log("Error saving email: " . $conn->error . " | Query: " . $sql);
                    http_response_code(500);
                    echo json_encode(["error" => "Error saving email. Please try again later."]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Email is required."]);
        }
    }

    // Close the connection
    $conn->close();
    ?>