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
    
        $_SESSION['student_logged_in'] = true;
        $_SESSION['student_email'] = $email;
        
        $student_stmt = $conn->prepare("SELECT id, name FROM students WHERE email=?");
        $student_stmt->bind_param("s", $email);
        $student_stmt->execute();
        $student_result = $student_stmt->get_result();
        if ($student = $student_result->fetch_assoc()) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['name'];
        }
        $student_stmt->close();
        
        // Delete OTP after use
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