<?php
include "db.php";

$id = $_GET['id'];

if (empty($id)) {
    echo json_encode(["error" => "Student ID is required"]);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->bind_param("s", $id);
$stmt->execute();

$res = $stmt->get_result();
$student = $res->fetch_assoc();

if (!$student) {
    echo json_encode(["error" => "Student not found"]);
} else {
    echo json_encode($student);
}

$stmt->close();
$conn->close();
?>