<?php
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM admin WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    if ($row['password'] === $password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $email;
        echo "success";
    } else {
        echo "Invalid";
    }
} else {
    echo "Invalid";
}

$stmt->close();
$conn->close();
?>