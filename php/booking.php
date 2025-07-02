<?php
session_start();
include 'db_conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get car ID from URL
$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;

// Fetch car details
$stmt = $conn->prepare("
    SELECT cars.*, GROUP_CONCAT(branches.name SEPARATOR ', ') AS branches 
    FROM cars
    JOIN car_branches ON cars.id = car_branches.car_id
    JOIN branches ON car_branches.branch_id = branches.id
    WHERE cars.id = ?
    GROUP BY cars.id
");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();

// Redirect if car not found
if (!$car) {
    header("Location: car-list.php");
    exit;
}

// Handle booking form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];
    $pickup_time = $_POST['pickup_time'];
    $return_time = $_POST['return_time'];
    $branch = $_POST['branch'];

    // Combine date & time into DateTime objects
$pickup_datetime = new DateTime($pickup_date . ' ' . $pickup_time);
$return_datetime = new DateTime($return_date . ' ' . $return_time);

// Current date & time
$now = new DateTime();

// Validate pickup is not in the past
if ($pickup_datetime < $now) {
    die("❌ Pickup date & time cannot be in the past.");
}

// Validate return is after pickup
if ($return_datetime <= $pickup_datetime) {
    die("❌ Return date & time must be after pickup date & time.");
}


    // Insert booking (assuming bookings table has pickup_time and return_time columns)
    $stmt = $conn->prepare("
        INSERT INTO bookings (user_id, car_id, pickup_date, return_date, pickup_time, return_time, pickup_location) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iisssss", 
        $_SESSION['user_id'], 
        $car_id, 
        $pickup_date, 
        $return_date, 
        $pickup_time, 
        $return_time, 
        $branch
    );
    $stmt->execute();

    $booking_id = $stmt->insert_id;

    header("Location: booking-success.php?booking_id=" . $booking_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Car - Troopers Car Rental</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/main.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid px-5">
    <a class="navbar-brand fw-bold text-uppercase" href="dashboard.php">
      <i class="fas fa-car me-2"></i> Drive Easy Car Rental
    </a>
  </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
  <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="car-list.php"><i class="fas fa-car"></i> Cars</a>
  <a href="myBookings.php" class="active"><i class="fas fa-calendar-check"></i> My Bookings</a>
  <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
</div>

<div class="main-content container mt-4">
  <h2 class="mb-4 text-primary">📅 Confirm Your Booking</h2>

  <!-- Car summary -->
  <div class="card car-summary-card shadow mb-4">
    <img src="../images/<?php echo htmlspecialchars($car['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['name']); ?>">
    <div class="card-body">
      <h5 class="card-title"><?php echo htmlspecialchars($car['name']); ?></h5>
      <p class="mb-1"><strong>Brand:</strong> <?php echo htmlspecialchars($car['brand']); ?></p>
      <p class="mb-1"><strong>Price per hour:</strong> RM<?php echo htmlspecialchars($car['price_per_hour']); ?></p>
      <p class="mb-1"><strong>Available at:</strong> <span class="badge bg-info"><?php echo htmlspecialchars($car['branches']); ?></span></p>
    </div>
  </div>

  <!-- Booking form -->
  <form method="post" class="shadow p-4 bg-light rounded">
    <h5 class="mb-3">📝 Booking Details</h5>
    <div class="mb-3">
      <label for="pickup_date" class="form-label">Pickup Date</label>
      <input type="date" name="pickup_date" id="pickup_date" class="form-control"
             min="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <div class="mb-3">
      <label for="pickup_time" class="form-label">Pickup Time</label>
      <input type="time" name="pickup_time" id="pickup_time" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="return_date" class="form-label">Return Date</label>
      <input type="date" name="return_date" id="return_date" class="form-control"
             min="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <div class="mb-3">
      <label for="return_time" class="form-label">Return Time</label>
      <input type="time" name="return_time" id="return_time" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="branch" class="form-label">Pickup Branch</label>
      <select name="branch" id="branch" class="form-select" required>
        <?php 
        $branches = explode(', ', $car['branches']);
        foreach ($branches as $b): ?>
          <option value="<?php echo htmlspecialchars($b); ?>"><?php echo htmlspecialchars($b); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-success">✅ Confirm Booking</button>
    <a href="car-list.php" class="btn btn-outline-secondary">← Back to Cars</a>
  </form>
</div>
</body>
</html>
