<?php
session_start();
unset($_SESSION['student_logged_in']);
unset($_SESSION['student_email']);
unset($_SESSION['student_id']);
unset($_SESSION['student_name']);
session_write_close();
header("Location: index.html");
exit();
?>