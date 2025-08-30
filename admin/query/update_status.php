<?php
// Set timezone to North Carolina Eastern Time
date_default_timezone_set('America/New_York');

require_once('../parts/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_id = isset($_POST['email_id']) ? intval($_POST['email_id']) : 0;
    $new_status = isset($_POST['status']) ? $_POST['status'] : '';
    $admin_email = isset($_POST['admin_email']) ? trim($_POST['admin_email']) : '';
    
    // If admin_email is provided, find the admin_id
    if (!empty($admin_email)) {
        $admin_query = "SELECT admin_id FROM admins WHERE admin_email = ?";
        $stmt = mysqli_prepare($conn, $admin_query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $admin_email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $admin_data = mysqli_fetch_assoc($result);
                $admin_id = $admin_data['admin_id'];
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Admin user not found'
                ]);
                exit();
            }
            mysqli_stmt_close($stmt);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Database error'
            ]);
            exit();
        }
    } else {
        // Set default admin_id for guest users
        $admin_id = 1; // Default admin ID for system operations
    }

    if ($email_id > 0 ) {
        // Check if user is trying to assign a new email and already has an assigned email
        if ($new_status == 'assigned' && !empty($admin_email)) {
            $check_assigned = "SELECT COUNT(*) as assigned_count FROM email WHERE admin_id = ? AND status = 'assigned'";
            $stmt_check = mysqli_prepare($conn, $check_assigned);
            if ($stmt_check) {
                mysqli_stmt_bind_param($stmt_check, "i", $admin_id);
                mysqli_stmt_execute($stmt_check);
                $result_check = mysqli_stmt_get_result($stmt_check);
                $assigned_data = mysqli_fetch_assoc($result_check);
                mysqli_stmt_close($stmt_check);
                
                if ($assigned_data['assigned_count'] > 0) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'You already have an assigned email. Please complete it before taking on a new one.'
                    ]);
                    exit();
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Database error while checking assignments'
                ]);
                exit();
            }
        }
        
        $now = date('Y-m-d H:i:s'); // Current time in North Carolina Eastern Time
        
        // Determine which timestamp field to update based on status
        $timestamp_field = '';
        if ($new_status == 'completed') {
            $timestamp_field = 'completed_at';
        } elseif ($new_status == 'assigned') {
            $timestamp_field = 'assigned_at';
        }
        
        // Build the update query with the appropriate timestamp field
        if ($timestamp_field) {
            $update = "UPDATE email SET status = '$new_status', $timestamp_field = '$now', admin_id = '$admin_id' WHERE id = $email_id";
        } else {
            $update = "UPDATE email SET status = '$new_status', admin_id = '$admin_id' WHERE id = $email_id";
        }

        if (mysqli_query($conn, $update)) {
            echo json_encode([
                'success' => true,
                'message' => "Status updated to '$new_status'"
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . mysqli_error($conn)
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid parameters'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

mysqli_close($conn);
?>
