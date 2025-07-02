<?php
session_start();
include 'db_conn.php';

// Redirect if not logged in or not admin
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Fetch recently added cars (you can customize this query)
$stmt = $conn->prepare("SELECT * FROM cars ORDER BY id DESC LIMIT 10");
$stmt->execute();
$cars = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recently Added Cars - Troopers Car Rental</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/responsive.css">

</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold text-uppercase" href="dashboard.php">
        <i class="fas fa-car me-2"></i> Troopers Car Rental
      </a>
    </div>
  </nav>

  <div class="dashboard">
    <!-- Sidebar -->
    <div class="sidebar">
      <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="car-list.php" class="active"><i class="fas fa-car"></i> Cars</a>
      <a href="booking.php"><i class="fas fa-calendar-check"></i> Bookings</a>
      <a href="#"><i class="fas fa-map-marker-alt"></i> Locations</a>
      <a href="#"><i class="fas fa-cog"></i> Settings</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
      <h1 class="mb-4">Recently Added Cars</h1>

      <div class="row">
        <?php while($car = $cars->fetch_assoc()): ?>
          <div class="col-md-4 mb-3">
            <div class="card h-100 shadow">
              <img src="../images/<?php echo htmlspecialchars($car['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['name']); ?>">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($car['name']); ?></h5>
                <p class="mb-1"><strong>Brand:</strong> <?php echo htmlspecialchars($car['brand']); ?></p>
                <p class="mb-1"><strong>Price per day:</strong> RM<?php echo htmlspecialchars($car['price_per_day']); ?></p>
                <!-- Add more details if you want -->
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div> <!-- End main-content -->
  </div> <!-- End dashboard -->
</body>
</html>
