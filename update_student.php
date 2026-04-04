<?php
include "db.php";

$id = $_POST['id'];
$name = $_POST['name'];

if (empty($id) || empty($name)) {
    die("ID and Name are required");
}

$stmt = $conn->prepare("UPDATE students SET name=? WHERE id=?");
$stmt->bind_param("ss", $name, $id);

if ($stmt->execute()) {
    echo "Updated Successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>