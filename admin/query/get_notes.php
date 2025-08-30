<?php
require_once('../parts/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

$email_id = $_GET['email_id'] ?? '';

if (empty($email_id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email ID is required'
    ]);
    exit();
}

try {
    // Fetch all notes for the email with admin information
    $query = "SELECT n.note_id, n.note_text, n.created_at, 
                     a.admin_name, a.admin_email, a.admin_role
              FROM email_notes n
              JOIN admins a ON n.admin_id = a.admin_id
              WHERE n.email_id = ?
              ORDER BY n.created_at DESC";
    
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $email_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $notes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $notes[] = [
                'note_id' => $row['note_id'],
                'note_text' => $row['note_text'],
                'created_at' => $row['created_at'],
                'admin_name' => $row['admin_name'],
                'admin_email' => $row['admin_email'],
                'admin_role' => $row['admin_role']
            ];
        }
        
        mysqli_stmt_close($stmt);
        
        echo json_encode([
            'success' => true,
            'notes' => $notes,
            'count' => count($notes)
        ]);
        
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . mysqli_error($conn)
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

mysqli_close($conn);
?>
