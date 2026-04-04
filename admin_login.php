<?php
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

// Use prepared statement with password verification
$stmt = $conn->prepare("SELECT * FROM admin WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $admin = $res->fetch_assoc();
    // Verify hashed password (you should store hashed passwords)
    if (password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $email;
        echo "success";
    } else {
        echo "Invalid";
    }
} else {
    echo "Invalid";
}
?>