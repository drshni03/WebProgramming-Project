<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$upload_dir = __DIR__ . '/../uploads/';

// Create uploads directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Handle file upload
$profile_picture = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = $_FILES['profile_picture']['type'];
    $file_size = $_FILES['profile_picture']['size'];
    
    if (in_array($file_type, $allowed_types) && $file_size <= 2097152) { // 2MB limit
        $file_ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
        $destination = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
            $profile_picture = $filename;
            // Delete old picture if it exists
            $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id=?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($old_picture);
            $stmt->fetch();
            $stmt->close();
            
            if (!empty($old_picture) && file_exists($upload_dir . $old_picture)) {
                unlink($upload_dir . $old_picture);
            }
        }
    }
}

// Update database
$sql = "UPDATE users SET 
        username = ?, 
        email = ?,
        phone_number = ?, 
        address = ?" . 
        ($profile_picture ? ", profile_picture = ?" : "") . 
        " WHERE id = ?";

$stmt = $conn->prepare($sql);
if ($profile_picture) {
    $stmt->bind_param("sssssi", 
        $_POST['username'], 
        $_POST['email'],
        $_POST['phone_number'], 
        $_POST['address'], 
        $profile_picture, 
        $user_id
    );
} else {
    $stmt->bind_param("ssssi", 
        $_POST['username'], 
        $_POST['email'],
        $_POST['phone_number'], 
        $_POST['address'], 
        $user_id
    );
}

if ($stmt->execute()) {
    // Update session username if changed
    if ($_POST['username'] != $_SESSION['username']) {
        $_SESSION['username'] = $_POST['username'];
    }
    header("Location: profile.php?updated=1");
} else {
    header("Location: edit-profile.php?error=1");
}
$stmt->close();
exit;
?>