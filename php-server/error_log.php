<?php

ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt'); 
ini_set('display_errors', 0);


trigger_error("This is a test error!", E_USER_NOTICE);


echo "Error has been logged.";
?>
