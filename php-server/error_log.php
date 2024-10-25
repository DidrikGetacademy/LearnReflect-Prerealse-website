<?php
// Enable error logging and set the log file
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt'); // Make sure this path is correct
ini_set('display_errors', 0); // Disable displaying errors on the browser

// Log a test error
trigger_error("This is a test error!", E_USER_NOTICE);

// Optional: Print a message indicating the script executed successfully
echo "Error has been logged.";
?>
