<?php
include "db.php";

$email = $_POST['email'];

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

// COMMENT OUT বা REMOVE এই চেকটি টেস্টিংয়ের জন্য
/*
$check = $conn->prepare("SELECT email FROM students WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows == 0) {
    die("Email not registered");
}
$check->close();
*/

$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// Delete old OTP
$stmt = $conn->prepare("DELETE FROM otp_codes WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

// Insert new OTP
$stmt = $conn->prepare("INSERT INTO otp_codes (email, otp, expiry_time) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $otp, $expiry);
$stmt->execute();

// For testing - show OTP
echo $otp;

$stmt->close();
$conn->close();
?>