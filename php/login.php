<?php
session_start();
include 'db_conn.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password, role, username FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password, $role, $db_username);  // Added username to bind_result
    
    if ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;
            $_SESSION['username'] = $db_username;  // Store username in session
            header('Location: index.php');
            exit;
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "No user found with that username!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login - MyCar Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Login</h2>
  <?php if($message): ?>
    <div class="alert alert-danger"><?php echo $message; ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label>Username:</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password:</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    <a href="register.php" class="btn btn-link">Don't have an account? Register</a>
  </form>
</div>
</body>
</html>