<?php
require_once('../parts/db.php');

header('Content-Type: application/json');

try {
    // Check for new emails with pending status
    $query = "SELECT COUNT(*) as pending_count FROM email WHERE status = 'pending'";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception('Database query failed: ' . mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($result);
    $pending_count = $row['pending_count'];
    
    // Get the last checked count from session or set to 0
    session_start();
    $last_count = isset($_SESSION['last_pending_count']) ? $_SESSION['last_pending_count'] : 0;
    
    // Check if there are new pending emails
    $new_emails = $pending_count > $last_count;
    
    // Update the last count in session
    $_SESSION['last_pending_count'] = $pending_count;
    
    // Debug information
    $debug_info = [
        'current_count' => $pending_count,
        'last_count' => $last_count,
        'new_emails' => $new_emails,
        'session_id' => session_id()
    ];
    
    echo json_encode([
        'success' => true,
        'pending_count' => $pending_count,
        'new_emails' => $new_emails,
        'last_count' => $last_count,
        'debug' => $debug_info
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conn);
?>
