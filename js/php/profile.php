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
  <title>My Profile - DriveEasy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    .profile-img {
      width: 150px;
      height: 150px;
      object-fit: cover;
    }
  </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">Profile updated successfully!</div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-body">
      <div class="text-center mb-4">
        <?php if (!empty($profile_picture)): ?>
          <img src="../uploads/<?php echo htmlspecialchars($profile_picture); ?>" class="rounded-circle profile-img">
        <?php else: ?>
          <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width:150px;height:150px;">
            <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
          </div>
        <?php endif; ?>
      </div>

      <div class="row">
        <div class="col-md-8 mx-auto">
          <table class="table table-bordered">
            <tr>
              <th width="30%">Username</th>
              <td><?php echo htmlspecialchars($username); ?></td>
            </tr>
            <tr>
              <th>Email</th>
              <td><?php echo htmlspecialchars($email); ?></td>
            </tr>
            <tr>
              <th>Phone Number</th>
              <td><?php echo htmlspecialchars($phone); ?></td>
            </tr>
            <tr>
              <th>Address</th>
              <td><?php echo htmlspecialchars($address); ?></td>
            </tr>
          </table>
          <div class="text-center">
            <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>