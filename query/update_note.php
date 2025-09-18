<?php
require_once('../parts/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

$email_id = $_POST['email_id'] ?? '';
$note_text = $_POST['note'] ?? '';

// Validate required fields
if (empty($email_id) || empty($note_text)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email ID and note are required'
    ]);
    exit();
}

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode([
        'success' => false,
        'message' => 'Authentication required. Please login.'
    ]);
    exit();
}

$admin_email = $_SESSION['admin_email'];

try {
    // Get admin data from session (already authenticated)
    $admin_id = $_SESSION['admin_id'];
    $admin_name = $_SESSION['admin_name'];
    
    // Insert new note record
    $insert_query = "INSERT INTO email_notes (admin_id, email_id, note_text) VALUES (?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $insert_query);
        
        if ($stmt_insert) {
            mysqli_stmt_bind_param($stmt_insert, "iis", $admin_id, $email_id, $note_text);
            
            if (mysqli_stmt_execute($stmt_insert)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Note added successfully',
                    'admin_name' => $admin_name,
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Database error: ' . mysqli_stmt_error($stmt_insert)
                ]);
            }
            
            mysqli_stmt_close($stmt_insert);
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
