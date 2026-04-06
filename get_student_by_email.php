<?php
include "db.php";

$email = $_GET['email'];

if (empty($email)) {
    echo json_encode(["error" => "Email is required"]);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Student not found"]);
}

$stmt->close();
$conn->close();
?>