<?php
header("Access-Control-Allow-Origin: https://www.learnreflects.com");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Inkluder konfigurasjonsfilen for databasen
include 'config.php'; // Antatt at config.php er i samme katalog

// Aktiver feilrapportering for utvikling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Opprett en forbindelse ved hjelp av legitimasjonen fra config.php
$conn = new mysqli($hostname, $username, $password, $database);

// Sjekk tilkoblingen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Vis nøyaktig feil
}

// Håndter POST-forespørselen
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Hent brukerens IP-adresse
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Rate limiting parametere
    $max_requests = 5; // maks antall forespørsel tillatt
    $time_limit = 60; // tidsbegrensning i sekunder

    // Sjekk forespørselstallet for IP-adressen
    $ip_check_query = "SELECT * FROM email_registration_ip WHERE ip_address = '$ip_address'";
    $ip_result = $conn->query($ip_check_query);

    if ($ip_result->num_rows > 0) {
        $ip_data = $ip_result->fetch_assoc();

        // Sjekk om den siste forespørselen var innen tidsbegrensningen
        $last_request_time = strtotime($ip_data['last_request']);
        $time_diff = time() - $last_request_time;

        if ($time_diff < $time_limit) {
            // Hvis forespørselstallet overskrider grensen
            if ($ip_data['request_count'] >= $max_requests) {
                http_response_code(429); // For mange forespørsel
                echo json_encode(["error" => "oopps! please try again later"]);
                exit;
            } else {
                // Øk forespørselstallet
                $update_query = "UPDATE email_registration_ip SET request_count = request_count + 1, last_request = NOW() WHERE ip_address = '$ip_address'";
                $conn->query($update_query);
            }
        } else {
            // Tilbakestill forespørselstallet etter tidsgrensen
            $update_query = "UPDATE email_registration_ip SET request_count = 1, last_request = NOW() WHERE ip_address = '$ip_address'";
            $conn->query($update_query);
        }
    } else {
        // Sett inn IP-adressen som en ny post
        $insert_query = "INSERT INTO email_registration_ip (ip_address, request_count) VALUES ('$ip_address', 1)";
        $conn->query($insert_query);
    }

    // Hent de sendte dataene
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->email)) {
        $email = $conn->real_escape_string($data->email);

        // Sjekk om e-posten allerede finnes i databasen
        $checkEmailQuery = "SELECT * FROM email_registration WHERE email = '$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            // E-posten finnes allerede
            http_response_code(409); // Konflikt
            echo json_encode(["error" => "Email already Registred"]);
        } else {
            // Forbered en SQL-setning for å sette inn e-posten i email_registration-tabellen
            $sql = "INSERT INTO email_registration (email) VALUES ('$email')";

            if ($conn->query($sql) === TRUE) {
                http_response_code(200);
                echo json_encode(["message" => "Email saved successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "error saving email: " . $conn->error]);
            }
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "E-post er påkrevd"]);
    }
}

$conn->close();
?>
