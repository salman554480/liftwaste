<?php
require_once('../parts/db.php');

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
$note_text = $_POST['note'] ?? '';
$admin_email = $_POST['admin_email'] ?? '';

// Validate required fields
if (empty($email_id) || empty($note_text) || empty($admin_email)) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required: email_id, note, admin_email'
    ]);
    exit();
}

try {
    // First authenticate the user by email only (session-based auth)
    $auth_query = "SELECT admin_id, admin_name, admin_role FROM admins WHERE admin_email = ?";
    $stmt_auth = mysqli_prepare($conn, $auth_query);
    
    if ($stmt_auth) {
        mysqli_stmt_bind_param($stmt_auth, "s", $admin_email);
        mysqli_stmt_execute($stmt_auth);
        $result_auth = mysqli_stmt_get_result($stmt_auth);
        
        if (mysqli_num_rows($result_auth) === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid admin email'
            ]);
            exit();
        }
        
        $admin_data = mysqli_fetch_assoc($result_auth);
        $admin_id = $admin_data['admin_id'];
        $admin_name = $admin_data['admin_name'];
        
        mysqli_stmt_close($stmt_auth);
        
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
                    'timestamp' => date('Y-m-d H:i:s') // Uses America/New_York timezone
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
