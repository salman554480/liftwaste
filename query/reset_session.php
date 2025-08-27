<?php
require_once('../parts/db.php');

header('Content-Type: application/json');

try {
    session_start();
    
    // Reset the notification count
    unset($_SESSION['last_pending_count']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Session reset successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
