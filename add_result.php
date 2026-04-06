<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Unauthorized: Admin login required");
}

include "db.php";

$id = $_POST['student_id'];
$sem = $_POST['semester'];
$sub = $_POST['subject'];
$marks = $_POST['marks'];
$gpa = $_POST['gpa'];

if (empty($id) || empty($sem) || empty($sub) || empty($marks)) {
    die("All fields are required");
}

$checkStmt = $conn->prepare("SELECT id FROM results WHERE student_id = ? AND semester = ? AND subject = ?");
$checkStmt->bind_param("sss", $id, $sem, $sub);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    
    $updateStmt = $conn->prepare("UPDATE results SET marks = ?, gpa = ? WHERE student_id = ? AND semester = ? AND subject = ?");
    $updateStmt->bind_param("ddsss", $marks, $gpa, $id, $sem, $sub);
    
    if ($updateStmt->execute()) {
        echo "Result Updated Successfully for $sub";
    } else {
        echo "Error updating result: " . $updateStmt->error;
    }
    $updateStmt->close();
} else {
    $insertStmt = $conn->prepare("INSERT INTO results (student_id, semester, subject, marks, gpa) VALUES (?, ?, ?, ?, ?)");
    $insertStmt->bind_param("sssdd", $id, $sem, $sub, $marks, $gpa);
    
    if ($insertStmt->execute()) {
        echo "New Result Added Successfully for $sub";
    } else {
        echo "Error adding result: " . $insertStmt->error;
    }
    $insertStmt->close();
}

$checkStmt->close();
$conn->close();
?>