<?php
require_once('../parts/db.php');

header('Content-Type: application/json');

try {
    // Fetch all emails with admin information
    $query = "SELECT e.*, a.admin_name as admin_username 
              FROM email e 
              LEFT JOIN admins a ON e.admin_id = a.admin_id 
              ORDER BY e.id ASC";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception('Database query failed: ' . mysqli_error($conn));
    }
    
    $emails = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Clean and format the data
        $emails[] = [
            'id' => $row['id'],
            'admin_id' => $row['admin_id'],
            'admin_username' => $row['admin_username'] ?: 'Not assigned',
            'sender' => htmlspecialchars($row['sender']),
            'sender_email' => htmlspecialchars($row['sender_email']),
            'recipient' => htmlspecialchars($row['recipient']),
            'subject' => htmlspecialchars($row['subject']),
            'body_text' => htmlspecialchars($row['body_text']),
            'body_html' => $row['body_html'], // Keep HTML as is for display
            'status' => $row['status'],
            'received_at' => $row['received_at'],
            'assigned_at' => $row['assigned_at'],
            'completed_at' => $row['completed_at']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'emails' => $emails,
        'count' => count($emails)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conn);
?>
