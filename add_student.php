<?php
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