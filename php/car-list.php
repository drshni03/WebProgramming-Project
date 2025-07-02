<?php
session_start();
include 'db_conn.php';

// Get all filter parameters
$brand = isset($_GET['brand']) ? trim($_GET['brand']) : '';
$model = isset($_GET['model']) ? trim($_GET['model']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 0;

// Build the base query
$query = "
    SELECT cars.*, branches.name AS branch_name, branches.address
    FROM cars 
    JOIN car_branches ON cars.id = car_branches.car_id
    JOIN branches ON car_branches.branch_id = branches.id
";

// Initialize conditions array and parameters for prepared statement
$conditions = [];
$params = [];
$types = '';

// Add conditions based on filters
if ($brand !== '') {
    $conditions[] = "cars.brand LIKE ?";
    $params[] = "%$brand%";
    $types .= 's';
}

if ($model !== '') {
    $conditions[] = "cars.name LIKE ?";
    $params[] = "%$model%";
    $types .= 's';
}

if ($location !== '') {
    $conditions[] = "(branches.name LIKE ? OR branches.address LIKE ?)";
    $params[] = "%$location%";
    $params[] = "%$location%";
    $types .= 'ss';
}

if ($min_price > 0) {
    $conditions[] = "cars.price_per_hour >= ?";
    $params[] = $min_price;
    $types .= 'd';
}

if ($max_price > 0 && $max_price >= $min_price) {
    $conditions[] = "cars.price_per_hour <= ?";
    $params[] = $max_price;
    $types .= 'd';
}

// Add WHERE clause if there are conditions
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Prepare and execute the query
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$cars = $stmt->get_result();

// Get all unique brands for dropdown
$brands_result = $conn->query("SELECT DISTINCT brand FROM cars ORDER BY brand");
$brands = [];
while ($row = $brands_result->fetch_assoc()) {
    $brands[] = $row['brand'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Available Cars - Drive Easy Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/main.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid px-5">
    <a class="navbar-brand fw-bold text-uppercase" href="index.php">
      <i class="fas fa-car me-2"></i> DriveEasy
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
    <a href="car-list.php" class="active"><i class="fas fa-car"></i> Cars</a>
    <a href="myBookings.php"><i class="fas fa-calendar-check"></i> My Bookings</a>
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
  </div>

  <!-- Main content -->
  <div class="main-content container mt-4">
    <h2 class="mb-4">
        <?php
        $filter_text = [];
        if ($brand !== '') $filter_text[] = "brand: " . htmlspecialchars($brand);
        if ($model !== '') $filter_text[] = "model: " . htmlspecialchars($model);
        if ($location !== '') $filter_text[] = "location: " . htmlspecialchars($location);
        if ($min_price > 0 || $max_price > 0) {
            $price_text = "price: ";
            if ($min_price > 0) $price_text .= "from RM" . htmlspecialchars($min_price);
            if ($max_price > 0) $price_text .= ($min_price > 0 ? " to " : "up to ") . "RM" . htmlspecialchars($max_price);
            $filter_text[] = $price_text;
        }
        
        if (!empty($filter_text)) {
            echo "Filtered Cars (" . implode(", ", $filter_text) . ")";
        } else {
            echo "All Available Cars";
        }
        ?>
    </h2>
    
    <!-- Enhanced Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" action="car-list.php" class="row g-3">
                <div class="col-md-3">
                    <select name="brand" class="form-select">
                        <option value="">All Brands</option>
                        <?php foreach ($brands as $b): ?>
                            <option value="<?php echo htmlspecialchars($b); ?>" 
                                    <?php if ($b === $brand) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($b); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="model" class="form-control" placeholder="Model (e.g., Camry)" 
                           value="<?php echo htmlspecialchars($model); ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="location" class="form-control" placeholder="Location" 
                           value="<?php echo htmlspecialchars($location); ?>">
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">RM</span>
                        <input type="number" name="min_price" class="form-control" placeholder="Min price" 
                               value="<?php echo $min_price > 0 ? htmlspecialchars($min_price) : ''; ?>" min="0">
                        <span class="input-group-text">to</span>
                        <input type="number" name="max_price" class="form-control" placeholder="Max price" 
                               value="<?php echo $max_price > 0 ? htmlspecialchars($max_price) : ''; ?>" min="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="car-list.php" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <?php if($cars && $cars->num_rows > 0): ?>
    <div class="row g-4">
      <?php while($car = $cars->fetch_assoc()): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <img src="../images/<?php echo htmlspecialchars($car['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['name']); ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($car['name']); ?></h5>
              <p class="card-text mb-1"><strong>Brand:</strong> <?php echo htmlspecialchars($car['brand']); ?></p>
              <p class="card-text mb-1"><strong>Branch:</strong> <?php echo htmlspecialchars($car['branch_name']); ?></p>
              <p class="card-text mb-1"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($car['address']); ?></p>
              <p class="card-text text-primary fw-semibold">From RM<?php echo htmlspecialchars($car['price_per_hour']); ?> per day</p>
              <a href="car-details.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    <?php else: ?>
      <div class="alert alert-info mt-4">
          No cars found <?php 
              if (!empty($filter_text)) {
                  echo "matching your filters";
              }
          ?>.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 mt-5">
  &copy; <?php echo date("Y"); ?> Drive Easy Car Rental
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Price range validation
    $('form').on('submit', function() {
        const minPrice = parseFloat($('[name="min_price"]').val()) || 0;
        const maxPrice = parseFloat($('[name="max_price"]').val()) || 0;
        
        if (maxPrice > 0 && maxPrice < minPrice) {
            alert('Maximum price must be greater than or equal to minimum price');
            return false;
        }
        return true;
    });
});
</script>
</body>
</html>
[file content end]