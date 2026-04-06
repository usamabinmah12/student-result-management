<?php
include "db.php";

$email = $_POST['email'];
$otp = $_POST['otp'];

$stmt = $conn->prepare("SELECT * FROM otp_codes WHERE email=? AND otp=? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (strtotime($row['expiry_time']) > time()) {
        $_SESSION['student'] = $email;
        
        $del = $conn->prepare("DELETE FROM otp_codes WHERE email=?");
        $del->bind_param("s", $email);
        $del->execute();
        $del->close();
        
        echo "success";
    } else {
        echo "OTP expired";
    }
} else {
    echo "Invalid OTP";
}

$stmt->close();
$conn->close();
?>