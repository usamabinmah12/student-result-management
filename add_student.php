<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Unauthorized: Admin login required");
}

include "db.php";

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$dept = $_POST['department'];

$stmt = $conn->prepare("INSERT INTO students (id, name, email, department) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $id, $name, $email, $dept);

if ($stmt->execute()) {
    echo "Added";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>