<?php
session_start();
include 'db_conn.php'; 

// Redirect if not logged in or not admin
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$date_today = date('Y-m-d');

// to get today's bookings count
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM bookings WHERE DATE(created_at) = ?");
$stmt->bind_param("s", $date_today);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$todays_bookings = $result['total'] ?? 0;

$stmt = $conn->prepare("
    SELECT SUM(DATEDIFF(return_date, pickup_date) * cars.price_per_day) as total_revenue
    FROM bookings
    JOIN cars ON bookings.car_id = cars.id
    WHERE DATE(bookings.created_at) = ?
");
$stmt->bind_param("s", $date_today);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$todays_revenue = $result['total_revenue'] ? '$' . number_format($result['total_revenue'], 2) : '$0';

// === Other stats (still hardcoded or make dynamic similarly) ===
$available_cars = 28;
$active_customers = 156;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Drive Easy Car Rental</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-5">
      <a class="navbar-brand fw-bold text-uppercase" href="dashboard.php">
        <i class="fas fa-car me-2"></i> Drive Easy Car Rental
      </a>
      <div class="d-flex">
      <a href="index.php" class="btn btn-outline-light">🏠 Home</a>
    </div>
    </div>
  </nav>

  <div class="dashboard">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-header text-center">
        <h4>Panel</h4>
      </div>
      <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="car-list.php"><i class="fas fa-car"></i> Cars</a>
      <a href="myBookings.php"><i class="fas fa-calendar-check"></i> My Bookings</a>
      <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
      <h1 class="mb-4">Dashboard Overview</h1>

      <!-- Stats cards -->
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card text-center shadow">
            <div class="card-body">
              <i class="fas fa-calendar-check fa-2x mb-2 text-primary"></i>
              <h5 class="card-title">Today's Bookings</h5>
              <p class="card-text fs-4"><?php echo $todays_bookings; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow">
            <div class="card-body">
              <i class="fas fa-car fa-2x mb-2 text-success"></i>
              <h5 class="card-title">Available Cars</h5>
              <p class="card-text fs-4"><?php echo $available_cars; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow">
            <div class="card-body">
              <i class="fas fa-users fa-2x mb-2 text-warning"></i>
              <h5 class="card-title">Active Customers</h5>
              <p class="card-text fs-4"><?php echo $active_customers; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow">
            <div class="card-body">
              <i class="fas fa-dollar-sign fa-2x mb-2 text-danger"></i>
              <h5 class="card-title">Today's Revenue</h5>
              <p class="card-text fs-4"><?php echo $todays_revenue; ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Bookings Table -->
      <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Recent Bookings</h5>
          <a href="booking.php" class="btn btn-primary btn-sm">View All</a>
        </div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Car</th>
                <th>Pickup Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#CR-1025</td>
                <td>Michael Brown</td>
                <td>Toyota Camry</td>
                <td>2023-06-30</td>
                <td><span class="badge bg-success">Confirmed</span></td>
                <td><button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button></td>
              </tr>
              <tr>
                <td>#CR-1024</td>
                <td>Sarah Johnson</td>
                <td>Honda Accord</td>
                <td>2023-06-29</td>
                <td><span class="badge bg-secondary">Completed</span></td>
                <td><button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button></td>
              </tr>
              <tr>
                <td>#CR-1023</td>
                <td>David Wilson</td>
                <td>Ford Mustang</td>
                <td>2023-06-28</td>
                <td><span class="badge bg-warning">Pending</span></td>
                <td><button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recently Added Cars -->
      <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Recently Added Cars</h5>
          <a href="recent-cars.php" class="btn btn-primary btn-sm">View All</a>
        </div>
        <div class="row p-3">
          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <img src="../images/camry.jpg" class="card-img-top" alt="Toyota Camry">
              <div class="card-body">
                <h6 class="card-title">Toyota Camry 2023</h6>
                <p class="mb-1"><i class="fas fa-gas-pump"></i> Hybrid</p>
                <p class="mb-1"><i class="fas fa-users"></i> 5 Seats</p>
                <p class="fw-bold">RM70/day</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <img src="../images/glc.jpg" class="card-img-top" alt="BMW X5">
              <div class="card-body">
                <h6 class="card-title">Mercedes-Benz GLC 2023</h6>
                <p class="mb-1"><i class="fas fa-gas-pump"></i> Diesel</p>
                <p class="mb-1"><i class="fas fa-users"></i> 5 Seats</p>
                <p class="fw-bold">RM350/day</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <img src="../images/alphard.jpg" class="card-img-top" alt="Honda Civic">
              <div class="card-body">
                <h6 class="card-title">Toyota Alphard 2023</h6>
                <p class="mb-1"><i class="fas fa-gas-pump"></i> Gasoline</p>
                <p class="mb-1"><i class="fas fa-users"></i> 8 Seats</p>
                <p class="fw-bold">RM320/day</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- End main-content -->
  </div> <!-- End dashboard -->
</body>
</html>
