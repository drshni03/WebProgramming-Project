<?php
session_start();
include 'db_conn.php';

// Verify database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords don't match";
    } elseif (strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters";
    } else {
        try {
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }
            
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $stmt->bind_result($hashed_password);
            
            if (!$stmt->fetch()) {
                throw new Exception("User not found");
            }
            $stmt->close();

            if (!password_verify($current_password, $hashed_password)) {
                throw new Exception("Current password is incorrect");
            }

            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            
            if (!$update_stmt) {
                throw new Exception("Database error: " . $conn->error);
            }
            
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt->bind_param("si", $new_hashed_password, $_SESSION['user_id']);
            
            if (!$update_stmt->execute()) {
                throw new Exception("Error updating password: " . $conn->error);
            }
            
            $success = "Password changed successfully!";
            $update_stmt->close();
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - DriveEasy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
<!-- Navbar -->
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
    <a href="myBookings.php"><i class="fas fa-calendar-check"></i> My Bookings</a>
    <a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a>
  </div>

  <!-- Main content -->
  <div class="main-content container mt-4">
    <h2 class="mb-4"><i class="fas fa-cog me-2"></i> Account Settings</h2>
    
    <div class="card shadow mb-4">
      <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-key me-2"></i> Change Password</h5>
      </div>
      <div class="card-body">
        <?php if ($error): ?>
          <div class="alert alert-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
          <div class="alert alert-success mb-4"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="post" class="row g-3">
          <div class="col-md-12">
            <label for="current_password" class="form-label">Current Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
          </div>
          
          <div class="col-md-6">
            <label for="new_password" class="form-label">New Password (min 8 characters)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-key"></i></span>
              <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
            </div>
          </div>
          
          <div class="col-md-6">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8">
            </div>
          </div>
          
          <div class="col-12">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-2"></i> Update Password
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</body>
</html>