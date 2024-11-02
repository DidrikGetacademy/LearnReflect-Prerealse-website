x|<?php
    // Set CORS headers
    header("Access-Control-Allow-Origin: https://www.learnreflects.com");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    // Include the database configuration file
    include 'config.php';
    include 'error_log.txt';
    include 'error_log.php';
    // Enable error reporting for development
    error_reporting(E_ALL);
    ini_set('log_errors', 1); // Enable error logging
    ini_set('error_log', 'error_log.txt'); // Point to the correct log file
    ini_set('display_errors', 0); // Disable displaying errors on the browser


    $conn = new mysqli($hostname, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error); // Log the connection error
        http_response_code(500); // Internal server error response
        echo json_encode(["error" => "Database connection failed."]);
        exit;
    }

    // Handle POST request
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Get user's IP address
        $ip_address = $_SERVER['REMOTE_ADDR'];

        // Rate limiting parameters
        $max_requests = 5; // Maximum number of allowed requests
        $time_limit = 60; // Time limit in seconds

        // Check the request count for the IP address
        $ip_check_query = "SELECT * FROM email_registration_ip WHERE ip_address = '$ip_address'";
        $ip_result = $conn->query($ip_check_query);

        if ($ip_result->num_rows > 0) {
            $ip_data = $ip_result->fetch_assoc();

            // Check if the last request was within the time limit
            $last_request_time = strtotime($ip_data['last_request']);
            $time_diff = time() - $last_request_time;

            if ($time_diff < $time_limit) {
                // If request count exceeds the limit
                if ($ip_data['request_count'] >= $max_requests) {
                    http_response_code(429); // Too many requests
                    echo json_encode(["error" => "Oops! Please try again later."]);
                    exit;
                } else {
                    // Increment request count
                    $update_query = "UPDATE email_registration_ip SET request_count = request_count + 1, last_request = NOW() WHERE ip_address = '$ip_address'";
                    $conn->query($update_query);
                }
            } else {
                // Reset request count after the time limit
                $update_query = "UPDATE email_registration_ip SET request_count = 1, last_request = NOW() WHERE ip_address = '$ip_address'";
                $conn->query($update_query);
            }
        } else {
            // Insert IP address as a new record
            $insert_query = "INSERT INTO email_registration_ip (ip_address, request_count) VALUES ('$ip_address', 1)";
            $conn->query($insert_query);
        }

        // Get the submitted data
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->email)) {
            $email = $conn->real_escape_string($data->email);

            // Check if the email already exists in the database
            $checkEmailQuery = "SELECT * FROM email_registration WHERE email = '$email'";
            $result = $conn->query($checkEmailQuery);

            if ($result->num_rows > 0) {
                // Email already exists
                //http_response_code(409); // Conflict
                echo json_encode(["error" => "Email already registered."]);
            } else {
                // Prepare a SQL statement to insert the email into the email_registration table
                $sql = "INSERT INTO email_registration (email) VALUES ('$email')";


                if ($conn->query($sql) === TRUE) {
                    http_response_code(200); // OK
                    echo json_encode(["message" => "Valid"]);
                } else {
                    // Log the error and send a response
                    error_log("Error saving email: " . $conn->error . " | Query: " . $sql);
                    http_response_code(500); // Internal server error
                    echo json_encode(["error" => "Error saving email. Please try again later."]);
                }
            }
        } else {
            http_response_code(400); // Bad request
            echo json_encode(["error" => "Email is required."]);
        }
    }

    // Close the connection
    $conn->close();
    ?>