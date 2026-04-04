<?php
include "db.php";

$id = $_POST['id'];

// First check if student exists
$check = $conn->prepare("SELECT id FROM students WHERE id=?");
$check->bind_param("s", $id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        echo "Deleted Successfully";
    } else {
        echo "Error deleting student";
    }
    $stmt->close();
} else {
    echo "Student not found";
}

$check->close();
$conn->close();
?>