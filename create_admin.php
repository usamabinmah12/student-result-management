<?php
include "db.php";

// অ্যাডমিন ইমেইল এবং পাসওয়ার্ড সেট করুন
$email = "admin@example.com";
$plain_password = "admin123";

// পাসওয়ার্ড হ্যাশ করুন
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// চেক করুন অ্যাডমিন ইতিমধ্যে আছে কিনা
$check = $conn->prepare("SELECT id FROM admin WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // আপডেট করুন
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    
    if ($stmt->execute()) {
        echo " Admin password updated successfully!<br>";
        echo "Email: admin@example.com<br>";
        echo "Password: admin123<br>";
    } else {
        echo " Error updating admin: " . $stmt->error;
    }
    $stmt->close();
} else {
    // ইনসার্ট করুন
    $stmt = $conn->prepare("INSERT INTO admin (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);
    
    if ($stmt->execute()) {
        echo " Admin created successfully!<br>";
        echo "Email: admin@example.com<br>";
        echo "Password: admin123<br>";
    } else {
        echo " Error creating admin: " . $stmt->error;
    }
    $stmt->close();
}

$check->close();
$conn->close();
?>