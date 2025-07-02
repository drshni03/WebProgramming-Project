<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch pending payments
$pending_stmt = $conn->prepare("
    SELECT bookings.*, cars.name AS car_name, cars.price_per_hour, cars.image,
           DATEDIFF(bookings.return_date, bookings.pickup_date) AS rental_days
    FROM bookings
    JOIN cars ON bookings.car_id = cars.id
    WHERE bookings.user_id = ? AND bookings.payment_status = 'Pending'
    ORDER BY bookings.pickup_date ASC
");
$pending_stmt->bind_param("i", $_SESSION['user_id']);
$pending_stmt->execute();
$pending_bookings = $pending_stmt->get_result();

// Fetch completed payments
$completed_stmt = $conn->prepare("
    SELECT bookings.*, cars.name AS car_name, cars.price_per_hour, cars.image,
           DATEDIFF(bookings.return_date, bookings.pickup_date) AS rental_days
    FROM bookings
    JOIN cars ON bookings.car_id = cars.id
    WHERE bookings.user_id = ? AND bookings.payment_status = 'Paid'
    ORDER BY bookings.pickup_date DESC
");
$completed_stmt->bind_param("i", $_SESSION['user_id']);
$completed_stmt->execute();
$completed_bookings = $completed_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings - DriveEasy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
<!-- Navbar (same as car-list.php) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid px-5">
    <a class="navbar-brand fw-bold text-uppercase" href="index.php">
      <i class="fas fa-car me-2"></i> Drive Easy Car Rental
    </a>
    <div class="d-flex align-items-center">
      <?php if(isset($_SESSION['user_id'])): ?>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <?php if(!empty($_SESSION['profile_picture'])): ?>
              <img src="../uploads/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" width="32" height="32" class="rounded-circle me-2">
            <?php else: ?>
              <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
            <?php endif; ?>
            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
            <li><a class="dropdown-item" href="edit-profile.php"><i class="fas fa-edit me-2"></i> Edit Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header text-center">
      <h4>Panel</h4>
    </div>
    <a href="car-list.php"><i class="fas fa-car"></i> Cars</a>
    <a href="myBookings.php" class="active"><i class="fas fa-calendar-check"></i> My Bookings</a>
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
  </div>

  <!-- Main content -->
  <div class="main-content container mt-4">
    <h2 class="mb-4"><i class="fas fa-calendar-alt me-2"></i> My Bookings</h2>
    
    <!-- Pending Payments Section -->
    <div class="mt-4">
        <h4 class="mb-3 section-title">Pending Payments</h4>
        <?php if ($pending_bookings->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php while($booking = $pending_bookings->fetch_assoc()): 
                    $total_price = $booking['price_per_hour'] * $booking['rental_days'];
                ?>
                    <div class="col">
                        <div class="card booking-card h-100">
                            <img src="../images/<?= htmlspecialchars($booking['image']) ?>" class="card-img-top car-img" alt="<?= htmlspecialchars($booking['car_name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($booking['car_name']) ?></h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-day"></i> 
                                        <?= date('M j, Y', strtotime($booking['pickup_date'])) ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-day"></i> 
                                        <?= date('M j, Y', strtotime($booking['return_date'])) ?>
                                    </small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge rounded-pill pending-badge">
                                        <i class="fas fa-clock"></i> Payment Pending
                                    </span>
                                    <h5 class="mb-0 text-primary">RM<?= number_format($total_price, 2) ?></h5>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="payment.php?booking_id=<?= $booking['id'] ?>" class="btn btn-success me-md-2">
                                        <i class="fas fa-credit-card"></i> Pay Now
                                    </a>
                                    <a href="cancel_booking.php?booking_id=<?= $booking['id'] ?>" class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No pending payments.
            </div>
        <?php endif; ?>
    </div>

    <!-- Completed Payments Section -->
    <div class="mt-5">
        <h4 class="mb-3 section-title">Completed Bookings</h4>
        <?php if ($completed_bookings->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php while($booking = $completed_bookings->fetch_assoc()): 
                    $total_price = $booking['price_per_day'] * $booking['rental_days'];
                    $can_cancel = strtotime($booking['pickup_date']) > time();
                ?>
                    <div class="col">
                        <div class="card booking-card h-100">
                            <img src="../images/<?= htmlspecialchars($booking['image']) ?>" class="card-img-top car-img" alt="<?= htmlspecialchars($booking['car_name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($booking['car_name']) ?></h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-day"></i> 
                                        <?= date('M j, Y', strtotime($booking['pickup_date'])) ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-day"></i> 
                                        <?= date('M j, Y', strtotime($booking['return_date'])) ?>
                                    </small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge rounded-pill completed-badge">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </span>
                                    <h5 class="mb-0 text-primary">RM<?= number_format($total_price, 2) ?></h5>
                                </div>
                                <?php if ($can_cancel): ?>
                                    <div class="d-grid">
                                        <a href="cancel_booking.php?booking_id=<?= $booking['id'] ?>" 
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to cancel this booking?');">
                                            <i class="fas fa-times"></i> Cancel Booking
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No completed bookings yet.
            </div>
        <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>