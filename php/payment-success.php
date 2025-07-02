<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Successful - Troopers Car Rental</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>
<div class="container mt-5 text-center">
  <h2 class="text-success mb-3">Payment Successful!</h2>
  <p class="mb-4">Thank you for your payment. Your booking has been confirmed.</p>

  <a href="car-list.php" class="btn btn-primary">Book Another Car</a>
  <a href="index.php" class="btn btn-secondary">Go to Home Page</a>
</div>
</body>
</html>
