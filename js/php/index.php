<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DriveEasy Car</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" /> 
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-5">
      <a class="navbar-brand navbar-brand-logo" href="index.php">
        <i class="fas fa-car"></i>
        <span>DriveEasy</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php if(isset($_SESSION['role'])): ?>
            <?php if($_SESSION['role'] == 'admin'): ?>
              <li class="nav-item"><a class="nav-link" href="dashboard.php">Admin Dashboard</a></li>
            <?php endif; ?>
              <li class="nav-item"><a class="nav-link" href="myBookings.php">My Bookings</a></li>
              <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
              <li class="nav-item"><a class="nav-link" href="#" onclick="confirmLogout(event)">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero section --> 
  <section class="welcome-hero d-flex align-items-center justify-content-center text-center text-white">
    <div>
      <h1>Welcome to DriveEasy<?php if(isset($_SESSION['username'])) echo ' ' . htmlspecialchars($_SESSION['username']); ?></h1>
      <p class="welcome-message">Book your car quickly and easily anytime, anywhere.</p>
      <a href="car-list.php" class="btn btn-primary mt-3">Explore Cars</a>
    </div>
  </section>

<!-- Location section --> 
<section class="search-section py-5 bg-light">
  <div class="container">
    <h3 class="text-center mb-4">Find Cars Near You</h3>
    <form action="car-list.php" method="get" class="row g-3 justify-content-center mb-4">
      <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Enter location or ZIP code" name="location">
      </div>
      <div class="col-md-3">
        <input type="datetime-local" class="form-control" name="pickup_date" placeholder="Pickup Date & Time">
      </div>
      <div class="col-md-3">
        <input type="datetime-local" class="form-control" name="return_date" placeholder="Return Date & Time">
      </div>
      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>
  <div id="map" style="height: 400px;"></div>
</section>
       
  <!-- Featured Brands section -->
  <section class="py-5">
    <div class="container">
      <h2 class="text-center mb-4">Featured Brands</h2>
      <div class="row g-4 justify-content-center">
        <div class="col-md-2 col-4">
          <a href="car-list.php?brand=Toyota" class="text-decoration-none text-dark">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-car fa-2x mb-2"></i>
                <h5 class="card-title">Toyota</h5>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-2 col-4">
          <a href="car-list.php?brand=Honda" class="text-decoration-none text-dark">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-car fa-2x mb-2"></i>
                <h5 class="card-title">Honda</h5>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-2 col-4">
          <a href="car-list.php?brand=Perodua" class="text-decoration-none text-dark">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-car fa-2x mb-2"></i>
                <h5 class="card-title">Perodua</h5>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-2 col-4">
          <a href="car-list.php?brand=Proton" class="text-decoration-none text-dark">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-car fa-2x mb-2"></i>
                <h5 class="card-title">Proton</h5>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-2 col-4">
          <a href="car-list.php?brand=Ford" class="text-decoration-none text-dark">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-car fa-2x mb-2"></i>
                <h5 class="card-title">Ford</h5>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-2 col-4">
          <a href="car-list.php?brand=BMW" class="text-decoration-none text-dark">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-car fa-2x mb-2"></i>
                <h5 class="card-title">BMW</h5>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-2 col-4">
          <a href="car-list.php?brand=Mercedes" class="text-decoration-none text-dark">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-car fa-2x mb-2"></i>
                <h5 class="card-title">Mercedes</h5>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-2 col-4">
          <a href="car-list.php?brand=Mazda" class="text-decoration-none text-dark">
            <div class="card text-center">
              <div class="card-body">
                <i class="fas fa-car fa-2x mb-2"></i>
                <h5 class="card-title">Mazda</h5>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3">
    &copy; <?php echo date("Y"); ?> MyCar Rental System
  </footer>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
    // Initialize the map, set view to Johor Bahru center & zoom level
    var map = L.map('map').setView([1.492659, 103.741359], 12);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Example: Add markers for your branches
    L.marker([1.492659, 103.741359]).addTo(map)
      .bindPopup('Main Branch');

    L.marker([1.485, 103.755]).addTo(map)
      .bindPopup('Airport Branch');

    L.marker([1.510, 103.730]).addTo(map)
      .bindPopup('Downtown Branch');
  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  function confirmLogout(event) {
    event.preventDefault(); // stop the link from navigating
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php"; // redirect to logout if confirmed
    }
}
</script>

</body>
</html>
