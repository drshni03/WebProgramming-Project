<?php
session_start();
include 'db_conn.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate
if (!$current_password || !$new_password || !$confirm_password) {
    header("Location: settings.php?pwd_error=Missing fields");
    exit;
}

if ($new_password !== $confirm_password) {
    header("Location: settings.php?pwd_error=New passwords do not match");
    exit;
}

// Fetch current password hash
$stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($current_password, $hash)) {
    header("Location: settings.php?pwd_error=Current password is incorrect");
    exit;
}

// Update to new password
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si", $new_hash, $user_id);
$stmt->execute();
$stmt->close();

header("Location: settings.php?pwd_success=Password changed successfully");
exit;
?>
