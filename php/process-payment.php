<?php
session_start();
include 'db_conn.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get payment data from form
$payment_method = $_POST['payment_method'] ?? '';
$card_number = $_POST['card_number'] ?? '';
$expiry = $_POST['expiry'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$bank_name = $_POST['bank_name'] ?? '';
$ewallet_name = $_POST['ewallet_name'] ?? '';

$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;

// Validate
if (!$payment_method || $booking_id <= 0) {
    die("Invalid request.");
}

// Update bookings table
$stmt = $conn->prepare("
    UPDATE bookings 
    SET payment_status = 'paid', payment_method = ?
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("sii", $payment_method, $booking_id, $_SESSION['user_id']);
$stmt->execute();

// Redirect to payment success page
header("Location: payment-success.php");
exit;
?>
