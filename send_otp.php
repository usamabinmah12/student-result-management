<?php
include "db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$email = $_POST['email'];

if (empty($email)) {
    die("Email is required");
}


$check = $conn->prepare("SELECT name FROM students WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows == 0) {
    die("Email not registered. Please contact admin.");
}

$check->bind_result($student_name);
$check->fetch();
$check->close();

$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));


$stmt = $conn->prepare("DELETE FROM otp_codes WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$stmt = $conn->prepare("INSERT INTO otp_codes (email, otp, expiry_time) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $otp, $expiry);
$stmt->execute();
$stmt->close();

$mail = new PHPMailer(true);

try {
    
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'usamabinmahbub12@gmail.com';  // আপনার Gmail ইমেইল দিন
    $mail->Password   = 'xuzu mody fzgo ubxs';   // আপনার App Password দিন (স্পেস সহ)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    
    
    $mail->setFrom('your_email@gmail.com', 'Metropolitan University');
    $mail->addAddress($email, $student_name);
    
    
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP for Student Login - Metropolitan University';
    $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { padding: 20px; background: #f5f5f5; }
                .header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; text-align: center; }
                .otp-box { background: #4CAF50; color: white; padding: 15px; font-size: 28px; text-align: center; border-radius: 5px; letter-spacing: 5px; }
                .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Metropolitan University</h2>
                    <p>Student Login Verification</p>
                </div>
                <div style='padding: 20px;'>
                    <h3>Dear $student_name,</h3>
                    <p>Your One-Time Password (OTP) for login is:</p>
                    <div class='otp-box'>
                        <strong>$otp</strong>
                    </div>
                    <p>This OTP is valid for <strong>5 minutes</strong>.</p>
                    <p>If you didn't request this, please ignore this email.</p>
                </div>
                <div class='footer'>
                    <p>© 2025 Metropolitan University | All Rights Reserved</p>
                </div>
            </div>
        </html>
    ";
    
    $mail->AltBody = "Dear $student_name,\n\nYour OTP for login is: $otp\n\nThis OTP is valid for 5 minutes.\n\nMetropolitan University";
    
    $mail->send();
    echo "success";
    
} catch (Exception $e) {
    
    echo $otp;
}

$conn->close();
?>