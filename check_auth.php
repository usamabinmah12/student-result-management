<?php
session_start();

header('Content-Type: application/json');

$response = [
    'student_logged_in' => false,
    'admin_logged_in' => false,
    'student_email' => null,
    'student_name' => null,
    'student_id' => null,
    'admin_email' => null
];

if (isset($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] === true) {
    $response['student_logged_in'] = true;
    $response['student_email'] = $_SESSION['student_email'] ?? null;
    $response['student_name'] = $_SESSION['student_name'] ?? null;
    $response['student_id'] = $_SESSION['student_id'] ?? null;
}
    
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $response['admin_logged_in'] = true;
    $response['admin_email'] = $_SESSION['admin_email'] ?? null;
}

echo json_encode($response);
?>