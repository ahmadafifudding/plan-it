<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer autoload

function sendOTPEmail($to, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'najihafarhana4@gmail.com';     // Your Gmail
        $mail->Password   = 'hwuc iuhc fjvn kitb';        // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('najihafarhana4@gmail.com', 'PLANIT');
        $mail->addAddress($to);
        $mail->Subject = 'Your OTP Code for PLANIT';
        $mail->Body    = "Your OTP code is: $otp";

        $mail->send();
    } catch (Exception $e) {
        echo "OTP email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
