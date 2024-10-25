<<<<<<< HEAD
<?php
header("Access-Control-Allow-Origin: https://www.learnreflects.com");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
// Include the database configuration file
include 'config.php';  // Assumes config.php is in the same directory

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a connection using the credentials from config.php
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Show the exact error
}

// Handle the POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the posted data
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->email)) {
        $email = $conn->real_escape_string($data->email);

        // Check if the email already exists in the database
        $checkEmailQuery = "SELECT * FROM email_registration WHERE email = '$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            // Email already exists
            http_response_code(409); // Conflict
            echo json_encode(["error" => "Email is already registered."]);
        } else {
            // Prepare an SQL statement to insert email into the email_registration table
            $sql = "INSERT INTO email_registration (email) VALUES ('$email')";

            if ($conn->query($sql) === TRUE) {
                http_response_code(200);
                echo json_encode(["message" => "Email saved successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to save email: " . $conn->error]);
            }
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Email is required"]);
    }
}

$conn->close();
?>
=======
<?php
header("Access-Control-Allow-Origin: https://www.learnreflects.com");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");


include 'config.php';  
include 'error_log.txt';
include 'error_log.php';


// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', 'error_log.txt'); // Point to the correct log file

// Create a connection using the credentials from config.php
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
}

// Handle the POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the posted data
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->email)) {
        $email = $conn->real_escape_string($data->email);

        // Check if the email already exists in the database
        $checkEmailQuery = "SELECT * FROM email_registration WHERE email = '$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            // Email already exists
            http_response_code(409); // Conflict
            echo json_encode(["error" => "Email is already registered."]);
        } else {
            // Prepare an SQL statement to insert email into the email_registration table
            $sql = "INSERT INTO email_registration (email) VALUES ('$email')";

            if ($conn->query($sql) === TRUE) {
                http_response_code(200);
                echo json_encode(["message" => "Email saved successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to save email: " . $conn->error]);
            }
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Email is required"]);
    }
}

$conn->close();
?>
>>>>>>> e92031d0 (updated github)
