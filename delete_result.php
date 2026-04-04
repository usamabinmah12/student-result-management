<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Unauthorized: Admin login required");
}

include "db.php";

$student_id = $_POST['student_id'];
$semester = $_POST['semester'];
$subject = $_POST['subject'];

$stmt = $conn->prepare("DELETE FROM results WHERE student_id = ? AND semester = ? AND subject = ?");
$stmt->bind_param("sss", $student_id, $semester, $subject);

if ($stmt->execute()) {
    echo "Subject deleted successfully!";
} else {
    echo "Error deleting subject: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>