<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_conn.php';

// Check admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get form data
$customer_id = $_POST['customer_id'];
$car_id = $_POST['car_id'];
$pickup = $_POST['pickup_date'];
$return = $_POST['return_date'];
$pickup_location = $_POST['pickup_location'];
$return_location = $_POST['return_location'];
$special_requests = $_POST['special_requests'];
$status = $_POST['status'];

// Additional services as JSON
$services = isset($_POST['services']) ? json_encode($_POST['services']) : json_encode([]);

// Insert into database
$sql = "INSERT INTO bookings (customer_id, car_id, pickup_date, return_date, pickup_location, return_location, special_requests, status, services)
        VALUES ('$customer_id', '$car_id', '$pickup', '$return', '$pickup_location', '$return_location', '$special_requests', '$status', '$services')";

if (mysqli_query($conn, $sql)) {
    echo "Booking added successfully. <a href='booking.php'>Back</a>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
