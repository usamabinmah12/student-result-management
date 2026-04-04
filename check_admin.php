<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    echo json_encode(["admin" => true, "email" => $_SESSION['admin']]);
} else {
    echo json_encode(["admin" => false]);
}
?>