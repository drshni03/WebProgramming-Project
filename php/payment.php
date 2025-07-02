<?php
session_start();
include 'db_conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check booking_id
if (!isset($_GET['booking_id'])) {
    die("❌ Booking ID missing!");
}
$booking_id = intval($_GET['booking_id']);

// Fetch booking details
$stmt = $conn->prepare("
    SELECT bookings.*, cars.name AS car_name, cars.price_per_hour
    FROM bookings
    JOIN cars ON bookings.car_id = cars.id
    WHERE bookings.id = ? AND bookings.user_id = ?
");
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    die("❌ Booking not found.");
}

// use both date and time to get full datetime
$pickup_datetime = new DateTime($booking['pickup_date'] . ' ' . $booking['pickup_time']);
$return_datetime = new DateTime($booking['return_date'] . ' ' . $booking['return_time']);

// calculate difference
$interval = $pickup_datetime->diff($return_datetime);
$total_minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
$total_hours = ceil($total_minutes / 60);
if ($total_hours <= 0) $total_hours = 1;

// Price calculation
$price_per_hour = $booking['price_per_hour'];
$deposit = 100;
$final_rental_price = $total_hours * $price_per_hour;
$total = round($final_rental_price + $deposit, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment - Drive Easy Car Rental</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #d0e8f2, #ffffff);
      min-height: 100vh;
    }
    .payment-card {
      max-width: 550px;
      margin: auto;
    }
    .header-gradient {
      background: linear-gradient(90deg, #0d6efd, #6610f2);
      color: #fff;
      border-radius: 0.5rem 0.5rem 0 0;
      padding: 1rem;
    }
  </style>
</head>
<body>
<div class="container mt-5 payment-card">
  <div class="card shadow">
    <div class="header-gradient text-center">
      <h2 class="mb-0">💳 Payment for Booking #<?php echo $booking_id; ?></h2>
    </div>
    <div class="card-body">
      <p><strong>🚗 Car:</strong> <?php echo htmlspecialchars($booking['car_name']); ?></p>
      <p><strong>📅 Pickup:</strong> 
        <span class="badge bg-primary"><?php echo htmlspecialchars($booking['pickup_date']); ?></span> 
        <?php echo htmlspecialchars($booking['pickup_time']); ?>
      </p>
      <p><strong>📅 Return:</strong> 
        <span class="badge bg-primary"><?php echo htmlspecialchars($booking['return_date']); ?></span> 
        <?php echo htmlspecialchars($booking['return_time']); ?>
      </p>
      <p><strong>⏱ Total Duration:</strong> <span class="text-success fw-bold"><?php echo $total_hours; ?> hour(s)</span></p>
      <p><strong>💰 Total Price:</strong> <span class="text-danger fw-bold">RM<?php echo number_format($total, 2); ?></span> 
        <small class="text-muted">(includes deposit of RM<?php echo $deposit; ?>)</small>
      </p>
      <hr>
      <form method="post" action="process-payment.php">
        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
        <div class="mb-3">
          <label class="form-label">Payment Method</label>
          <select name="payment_method" class="form-select" required>
            <option value="">Select...</option>
            <option value="Credit Card">Credit Card</option>
            <option value="Online Banking">Online Banking</option>
            <option value="E-Wallet">E-Wallet</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Card Number</label>
          <input type="text" name="card_number" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX">
        </div>
        <div class="row">
          <div class="col mb-3">
            <label class="form-label">Expiry Date</label>
            <input type="text" name="expiry" class="form-control" placeholder="MM/YY">
          </div>
          <div class="col mb-3">
            <label class="form-label">CVV</label>
            <input type="text" name="cvv" class="form-control" placeholder="123">
          </div>
        </div>
        <button type="submit" class="btn btn-success w-100 mb-2">
          ✅ Pay RM<?php echo number_format($total, 2); ?>
        </button>
        <a href="booking-success.php" class="btn btn-secondary w-100">← Back to Previous Page</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
