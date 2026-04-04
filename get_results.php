<?php
include "db.php";

$id = $_GET['id'];
$semester = isset($_GET['semester']) ? $_GET['semester'] : null;

if (empty($id)) {
    echo json_encode(["error" => "Student ID is required"]);
    exit();
}

// Check if student exists
$checkStudent = $conn->prepare("SELECT * FROM students WHERE id=?");
$checkStudent->bind_param("s", $id);
$checkStudent->execute();
$studentResult = $checkStudent->get_result();

if ($studentResult->num_rows == 0) {
    echo json_encode(["error" => "Student not found"]);
    exit();
}

// Query results
if ($semester) {
    $stmt = $conn->prepare("SELECT * FROM results WHERE student_id=? AND semester=? ORDER BY subject ASC");
    $stmt->bind_param("ss", $id, $semester);
} else {
    $stmt = $conn->prepare("SELECT * FROM results WHERE student_id=? ORDER BY semester ASC, subject ASC");
    $stmt->bind_param("s", $id);
}

$stmt->execute();
$res = $stmt->get_result();

$results_by_sem = [];
$total_gpa = 0;
$total_subjects = 0;

while ($row = $res->fetch_assoc()) {
    $sem = $row['semester'];
    if (!isset($results_by_sem[$sem])) {
        $results_by_sem[$sem] = [];
    }
    $results_by_sem[$sem][] = $row;
    
    if ($row['gpa'] != NULL && $row['gpa'] !== '') {
        $total_gpa += (float)$row['gpa'];
        $total_subjects++;
    }
}

$cgpa = $total_subjects ? round($total_gpa / $total_subjects, 2) : 0;

echo json_encode([
    "results" => $results_by_sem,
    "cgpa" => $cgpa,
    "has_results" => ($total_subjects > 0)
]);

$stmt->close();
$checkStudent->close();
$conn->close();
?>