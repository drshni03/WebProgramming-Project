<?php
session_start();
include 'db_conn.php';

// Validate and get car_id from URL
$carId = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;

// Fetch car data safely
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->bind_param("i", $carId);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Details - Drive Easy Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Drive Easy Car Rental</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['role'])): ?>
          <?php if($_SESSION['role'] == 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Admin Dashboard</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="car-list.php">Cars</a></li>
            <li class="nav-item"><a class="nav-link" href="myBookings.php">My Bookings</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="php/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <?php if($car): ?>
    <div class="row">
      <div class="col-md-6">
        <img src="../images/<?php echo htmlspecialchars($car['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($car['name']); ?>">
      </div>
      <div class="col-md-6">
        <h2><?php echo htmlspecialchars($car['name']); ?></h2>
        <p><strong>Brand:</strong> <?php echo htmlspecialchars($car['brand']); ?></p>
        <p><strong>Price:</strong> RM<?php echo htmlspecialchars($car['price_per_hour']); ?> per day</p>
        <p><?php echo htmlspecialchars($car['description']); ?></p>
        
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'user'): ?>
          <a href="booking.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary">Book Now</a>
        <?php else: ?>
          <p class="text-muted">Please log in as a user to book this car.</p>
        <?php endif; ?>
        <a href="car-list.php" class="btn btn-secondary mt-2">Back to Car List</a>
      </div>
    </div>
  <?php else: ?>
    <p>Car not found.</p>
    <a href="car-list.php" class="btn btn-secondary">Back to Car List</a>
  <?php endif; ?>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
  &copy; <?php echo date("Y"); ?> Drive Easy Rental System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
