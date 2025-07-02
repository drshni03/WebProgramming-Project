<?php
session_start();
include 'db_conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;

if ($booking_id > 0) {
    // Verify the booking belongs to the user and is pending
    $verify_stmt = $conn->prepare("SELECT user_id, status FROM bookings WHERE id = ?");
    $verify_stmt->bind_param("i", $booking_id);
    $verify_stmt->execute();
    $verify_stmt->bind_result($user_id, $status);
    $verify_stmt->fetch();
    $verify_stmt->close();
    
    if ($user_id == $_SESSION['user_id'] && $status == 'Confirmed') {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Update booking status
            $update_stmt = $conn->prepare("UPDATE bookings SET status = 'Cancelled', cancelled_at = NOW() WHERE id = ?");
            $update_stmt->bind_param("i", $booking_id);
            
            if (!$update_stmt->execute()) {
                throw new Exception("Failed to update booking status");
            }
            
            // Record cancellation in audit log
            $audit_stmt = $conn->prepare("INSERT INTO booking_audit_log (booking_id, action, user_id) VALUES (?, 'Cancelled', ?)");
            $audit_stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
            
            if (!$audit_stmt->execute()) {
                throw new Exception("Failed to record audit log");
            }
            
            $conn->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid booking or unauthorized action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid booking ID']);
}
?>
[file content end]