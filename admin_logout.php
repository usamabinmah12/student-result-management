<?php
session_start();
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_email']);
session_write_close();
header("Location: admin.html");
exit();
?>