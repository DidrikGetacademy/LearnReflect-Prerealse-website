<?php
ob_start();



error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');
ini_set('display_errors', 0);
ini_set('memory_limit', '2048M');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendEmailResetPasswordToken($forgottenPasswordEmail,$resetToken){
    error_log("SendMail function called for $forgottenPasswordEmail");
    include 'config.php';
    $mail = new PHPMailer(true);
    try{
        error_log("In start of try block $forgottenPasswordEmail");
        $mail->isSMTP();
        $mail->Host = 'send.one.com';
        $mail->SMTPAuth = true;
        $email->Username = 'learnreflects@learnreflects.com';
        $mail->Password =$EmailPassword;
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->setFrom('learnreflects@learnreflects.com');
        $mail->addAddress($forgottenPasswordEmail);

        $mail->Subject = 'Password Reset Reqest';
        $mail->Body = "Hello,\n\nWe received a request to reset your password. Please use the following token to reset your password:\n\n" .
        "Reset Token: $resetToken\n\n" .
        "If you did not request a password reset, please ignore this email.";

        $mail->send();
        error_log("Reset token email sent successfully to $forgottenPasswordEmail");
    }catch(Exception $e){
        error_log("Mailer Error: {$mail->ErrorInfo}");
    }

}

function SendMail($recipient_email, $name, $key_code)
{
    error_log("SendMail function called for $recipient_email");
    include 'config.php';
    $mail = new PHPMailer(true);

    try {
        error_log("In start of try block $recipient_email");
        $mail->isSMTP();
        $mail->Host = 'send.one.com';              
        $mail->SMTPAuth = true;                  
        $mail->Username = 'learnreflects@learnreflects.com';  
        $mail->Password = $EmailPassword;  
        $mail->Port = 465;                         
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    

        $mail->setFrom('learnreflects@learnreflects.com', 'LearnReflects');
        $mail->addAddress($recipient_email, $name);
        $zipFilePath = 'Software/LearnReflectEnchancer.zip'; 


        $mail->addAttachment($zipFilePath, 'LearnReflectEnchancer.zip'); 
       if (file_exists($zipFilePath)) {
          $mail->addAttachment($zipFilePath, 'LearnReflectEnchancer.zip'); 
         error_log("Attachment added successfully.");
       } else {
           error_log("Error: The file $zipFilePath does not exist.");
           $mail->Body .= "<br><br><b>Error:</b> The requested software file is unavailable.";
       }

        $mail->isHTML(true);
        $mail->Subject = 'Your software download & Activasion key';
        $mail->Body    = "Here is the software you requested. You can download it from the attached zip file.<br><br>Dear $name,<br><br>Your activation key is: <b>$key_code</b><br><br>Thank you for your purchase!";
        $mail->AltBody = "Dear $name,\n\nYour activation key is: $key_code\n\nThank you for your purchase!";
        $mail->SMTPDebug = 2; // Enables SMTP debug output
        $mail->Debugoutput = function ($str, $level) {
            error_log("SMTP Debug level $level: $str");
        };

        if ($mail->send()) {
            error_log("Email sent successfully to " . $recipient_email);
        } else {
            error_log("Failed to send to " . $recipient_email . 'PHPMailer error: ' . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        error_log('Mailer Exception ' . $e->getMessage());
    } finally {
        error_log("Hit the finally block in send_email.php");
        ob_end_flush();
    }
}
