<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if not authenticated
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = "Please login to access this page";
    header("Location: login.php");
    exit();
}

// Check admin privileges
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error_msg'] = "Admin privileges required";
    header("Location: dashboard.php");
    exit();
}
?>