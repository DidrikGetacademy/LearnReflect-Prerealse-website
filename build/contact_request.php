<?php
# Access and api Configs
header("Access-Control-Allow-Origin: https://www.learnreflects.com"); #Konfigurasjon CORS for å tillate tilgang fra en bestemt URL 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); # tilatter metoder for api request
header("Access-Control-Allow-Headers: Content-Type, Authorization"); #tilatter spesifikke oversikter
header("Access-Control-Allow-Credentials: true"); #Tilatter overføring av cookies eller andre autiniseringsdataer.



# includes the specified files.
include 'config.php'; #inkluderer konfigurasjonsfil for database tilkobling
include 'error_log.php'; # inkluderer konfigurasjon for errorlogging. 


#enable error logging, disabled in browser.
error_reporting(E_ALL); # report all errors
ini_set('log_errors', 1); # aktiverer logging av feil til fil.
ini_set('error_log', 'error_log.txt'); #sett feil-loggfilsnavnet til 'error_log.txt'
ini_set('display_errors', 0); #slå av feilvisning i nettleseren (sikkerhetsmessig for å ikke vise detaljer til brukeren.)


#connection details/config
$conn = new mysqli($hostname, $username, $password, $database);


#Checks connection too the database
if ($conn->connect_error) {
    error_log("Connection Failed: " . $conn->connect_error); // log the connection error
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]); #returnerer en json-feilmelding til klienten
    exit; #hvis det oppstår feil, avslutter koden ved feil.
}


#Sjekker om HTTP-Forespørselen er en POST-forespørsel.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    #leser json data som blir sendt fra UI
    $data = json_decode(file_get_contents("php://input"), true);

    error_log("Recived Data:" . print_r($data, true));
    $email = isset($data["EmailAddress"]) ? $conn->real_escape_string($data['EmailAddress']) : null; #henter epost og gjør den skikket for SQL ved og fjerne spesialtegn.
    $category = isset($data['Category']) ? $conn->real_escape_string($data['Category']) : null; #henter category og gjør den skikket for sql ved og fjerne spesialtegn.
    $Description = isset($data['Description']) ? $conn->real_escape_string($data['Description']) : null; #henter Description og gjør den skikket for sql ved og fjerne spesialtegn
    error_log("Email" . $email);
    error_log("Category: " . $category);
    error_log("Description " . $Description);

    #sjekker at både email og kategori har en verdi i data fra frontend
    if ($email && $category && $Description) {
        #sql-setning for å lagre informasjon i databasen.
        $sql = "INSERT INTO contacts (EmailAddress, Category, Description) VALUES ('$email', '$category','$Description')";


        if ($conn->query($sql) === true) {
            http_response_code(200); // setter status kode til sukssess.
            echo json_encode(["message" => "Contact Request Recived Successfully"]); #returnerer suksess melding i json-format.
        } else {
            error_log("SQL Error: " . $conn->error); // Logg SQL-feil hvis det oppstår.
            http_response_code(500); // Sett http statuskode til 500 (intern serverfeil)
            echo json_encode(["error" => "Failed to save contact request."]); #returnerer database error i json format tilbake til UI
        }
    } else {
        http_response_code(400); # sett HTTP statuskode til 400 (dårlig forespørsel)
        echo json_encode(["error" => "Invalid input data."]); # returnerer feil melding i JSON-format ikke alle verdier er gyldige.
    }
}
#lukker databasetilkoblingen
$conn->close(); #lukk databasekoblingen etter at alt er fullført.
