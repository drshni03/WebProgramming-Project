<?php
session_start();
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Success - Drive Easy Car Rental</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>
<div class="container mt-5 text-center">
  <h1 class="mb-4 text-success"><i class="fas fa-check-circle"></i> Booking Confirmed!</h1>
  <p class="lead">Thank you! Your booking has been successfully placed.</p>
  <p class="mb-4">What would you like to do next?</p>
  <a href="payment.php?booking_id=<?php echo $booking_id; ?>" class="btn btn-primary me-2">
    <i class="fas fa-credit-card"></i> Proceed to Payment
  </a>
  <a href="car-list.php" class="btn btn-secondary">
    <i class="fas fa-plus-circle"></i> Add Another Booking
  </a>
</div>
</body>
</html>
