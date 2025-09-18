<?php
require_once('parts/db.php');

// Set timezone to North Carolina Eastern Standard Time (same as IMAP connection)
date_default_timezone_set('America/New_York');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

$email_id = $_POST['email_id'] ?? '';

// Validate required fields
if (empty($email_id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email ID is required'
    ]);
    exit();
}

// Validate email ID is numeric
if (!is_numeric($email_id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email ID format'
    ]);
    exit();
}

try {
    // First check if the email exists
    $check_query = "SELECT id, subject, sender FROM email WHERE id = ?";
    $stmt_check = mysqli_prepare($conn, $check_query);
    
    if ($stmt_check) {
        mysqli_stmt_bind_param($stmt_check, "i", $email_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($result_check) === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Email not found'
            ]);
            exit();
        }
        
        $email_data = mysqli_fetch_assoc($result_check);
        $email_subject = $email_data['subject'];
        $email_sender = $email_data['sender'];
        
        mysqli_stmt_close($stmt_check);
        
        // Delete the email (this will also cascade delete related notes due to foreign key)
        $delete_query = "DELETE FROM email WHERE id = ?";
        $stmt_delete = mysqli_prepare($conn, $delete_query);
        
        if ($stmt_delete) {
            mysqli_stmt_bind_param($stmt_delete, "i", $email_id);
            
            if (mysqli_stmt_execute($stmt_delete)) {
                // Check if any rows were affected
                if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
                    echo json_encode([
                        'success' => true,
                        'message' => "Email '{$email_subject}' from '{$email_sender}' has been deleted successfully"
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'No email was deleted. Email may have already been removed.'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Database error: ' . mysqli_stmt_error($stmt_delete)
                ]);
            }
            
            mysqli_stmt_close($stmt_delete);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . mysqli_error($conn)
            ]);
        }
        
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
