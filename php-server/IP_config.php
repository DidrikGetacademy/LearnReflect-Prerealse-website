<?php
// Set JSON response headers
header('Content-Type: application/json');

// Handle CORS dynamically based on allowed origins
if (isset($_SERVER['HTTP_ORIGIN'])) {
    $allowed_origins = ['https://www.learnreflects.com', 'https://www.paypal.com'];
    if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
        header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");
    }
}

// Function to get user IP address
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ','); // Handle multiple IPs in case of proxy
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Get user IP address
$ip = getUserIP();

// Fetch geolocation data using IPInfo.io
$apiToken = "d2a4558130b10c"; // Replace with your IPInfo API token
$geoData = [];
$abuseData = [];

try {
    $apiUrl = "https://ipinfo.io/{$ip}/json?token={$apiToken}";
    $response = file_get_contents($apiUrl);
    if ($response === false) {
        throw new Exception("Failed to fetch geolocation data from IPInfo API.");
    }
    $geoData = json_decode($response, true); // Geolocation data
    $abuseData = $geoData['abuse'] ?? []; // Extract the 'abuse' section
} catch (Exception $e) {
    error_log("Error fetching geolocation data: " . $e->getMessage());
    // Default data if there's an error
    $geoData = [
        'city' => 'Unknown',
        'region' => 'Unknown',
        'country' => 'Unknown',
        'org' => 'Unknown',
        'asn' => 'Unknown',
        'company' => 'Unknown',
        'privacy' => 'Unknown',
        'domains' => 'Unknown'
    ];
    $abuseData = [
        'address' => 'Unknown',
        'email' => 'Unknown',
        'phone' => 'Unknown'
    ];
}

// Capture additional data from POST request
$data = json_decode(file_get_contents('php://input'), true);
$userAgent = $data['userAgent'] ?? 'Unknown';
$language = $data['language'] ?? 'Unknown';
$platform = $data['platform'] ?? 'Unknown';
$screenResolution = $data['screenResolution'] ?? 'Unknown';
$referrer = $data['referrer'] ?? 'Unknown';

// Prepare log data including all sections
$logFile = __DIR__ . '/iplog.txt'; // Local path to your log file
$logData = sprintf(
    "[%s] IP: %s | City: %s | Region: %s | Country: %s | ISP: %s | ASN: %s | Company: %s | Privacy VPN: %s | Privacy Proxy: %s | Domains: %s | User-Agent: %s | Language: %s | Platform: %s | Screen: %s | Referrer: %s | Abuse Address: %s | Abuse Email: %s | Abuse Phone: %s\n",
    date('Y-m-d H:i:s'),
    $ip,
    $geoData['city'] ?? 'Unknown',
    $geoData['region'] ?? 'Unknown',
    $geoData['country'] ?? 'Unknown',
    $geoData['org'] ?? 'Unknown',
    $geoData['asn'] ?? 'Unknown',
    $geoData['company']['name'] ?? 'Unknown',
    $geoData['privacy']['vpn'] ?? 'Unknown',
    $geoData['privacy']['proxy'] ?? 'Unknown',
    implode(', ', $geoData['domains'] ?? []),
    $userAgent,
    $language,
    $platform,
    $screenResolution,
    $referrer,
    $abuseData['address'] ?? 'Unknown',
    $abuseData['email'] ?? 'Unknown',
    $abuseData['phone'] ?? 'Unknown'
);

// Write log data to the file
try {
    file_put_contents($logFile, $logData, FILE_APPEND);
    error_log("Logged IP data to file: $logFile");
} catch (Exception $e) {
    error_log("Error writing to log file: " . $e->getMessage());
}

// Send a minimal response to avoid frontend errors
http_response_code(204); // No content response
?>




useEffect(() => {
    const browserData = {
        userAgent: navigator.userAgent, // Browser and OS information
        language: navigator.language,  // Browser language
        platform: navigator.platform,  // Device platform
        screenResolution: `${window.screen.width}x${window.screen.height}`, // Screen resolution
        referrer: document.referrer,   // Referring URL
        // You can also add other fields here such as time zone, etc.
        timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,  // Time zone information
    };

    // Send data to PHP endpoint
    fetch('https://learnreflects.com/Server/IP_config.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(browserData),
    }).catch((error) => console.error('Error contacting backend:', error));
  }, []);