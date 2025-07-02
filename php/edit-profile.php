<?php
session_start();
include 'db_conn.php';
include '../includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, phone_number, address, profile_picture FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $phone, $address, $profile_picture);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Profile - DriveEasy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-body">
      <h2 class="mb-4">Edit Profile</h2>
      <form action="update-profile.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Phone Number</label>
          <input type="tel" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($address); ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Profile Picture</label><br>
          <?php if (!empty($profile_picture)): ?>
            <img src="../uploads/<?php echo htmlspecialchars($profile_picture); ?>" width="100" class="mb-2"><br>
          <?php endif; ?>
          <input type="file" name="profile_picture" accept="image/*" class="form-control">
          <small class="text-muted">Max 2MB (JPEG, PNG, GIF)</small>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button type="submit" class="btn btn-success me-md-2">Save Changes</button>
          <a href="profile.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>