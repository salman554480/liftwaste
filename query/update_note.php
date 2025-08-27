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
$note = $_POST['note'] ?? '';

if (empty($email_id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email ID is required'
    ]);
    exit();
}

try {
    // Update the note in the email table
    $update_query = "UPDATE email SET note = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $note, $email_id);
        
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Note updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No changes made to the note'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . mysqli_stmt_error($stmt)
            ]);
        }
        
        mysqli_stmt_close($stmt);
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
