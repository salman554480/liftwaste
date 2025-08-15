<?php
require_once('../parts/db.php');
session_start();

header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 0;
    $admin_name = isset($_POST['admin_name']) ? trim($_POST['admin_name']) : '';
    $admin_email = isset($_POST['admin_email']) ? trim($_POST['admin_email']) : '';
    $admin_contact = isset($_POST['admin_contact']) ? trim($_POST['admin_contact']) : '';
    $admin_address = isset($_POST['admin_address']) ? trim($_POST['admin_address']) : '';
    $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validate required fields
    if (empty($admin_name) || empty($admin_email)) {
        echo json_encode([
            'success' => false,
            'message' => 'Name and email are required fields'
        ]);
        exit();
    }

    // Validate email format
    if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please enter a valid email address'
        ]);
        exit();
    }

    // Check if email already exists for other admins
    $check_email = "SELECT admin_id FROM admins WHERE admin_email = ? AND admin_id != ?";
    $stmt = mysqli_prepare($conn, $check_email);
    mysqli_stmt_bind_param($stmt, "si", $admin_email, $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Email address is already in use by another admin'
        ]);
        exit();
    }
    mysqli_stmt_close($stmt);

    // Handle password change
    $password_update = '';
    $password_params = [];
    
    if (!empty($new_password)) {
        // Current password is required for password change
        if (empty($current_password)) {
            echo json_encode([
                'success' => false,
                'message' => 'Current password is required to change password'
            ]);
            exit();
        }

        // Verify current password (plain text for testing)
        $verify_password = "SELECT admin_password FROM admins WHERE admin_id = ?";
        $stmt = mysqli_prepare($conn, $verify_password);
        mysqli_stmt_bind_param($stmt, "i", $admin_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin_data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$admin_data || $current_password !== $admin_data['admin_password']) {
            echo json_encode([
                'success' => false,
                'message' => 'Current password is incorrect'
            ]);
            exit();
        }

        // Validate new password
        if (strlen($new_password) < 6) {
            echo json_encode([
                'success' => false,
                'message' => 'New password must be at least 6 characters long'
            ]);
            exit();
        }

        if ($new_password !== $confirm_password) {
            echo json_encode([
                'success' => false,
                'message' => 'New password and confirmation password do not match'
            ]);
            exit();
        }

        // Store password as plain text for testing
        $password_update = ', admin_password = ?';
        $password_params[] = $new_password;
    }

    try {
        // Update admin information
        $update_query = "UPDATE admins SET 
                        admin_name = ?, 
                        admin_email = ?, 
                        admin_contact = ?, 
                        admin_address = ?" . $password_update . "
                        WHERE admin_id = ?";
        
        $stmt = mysqli_prepare($conn, $update_query);
        
        // Build parameters array
        $params = [$admin_name, $admin_email, $admin_contact, $admin_address];
        if (!empty($password_params)) {
            $params = array_merge($params, $password_params);
        }
        $params[] = $admin_id;
        
        // Bind parameters dynamically
        $types = str_repeat('s', count($params) - 1) . 'i'; // All strings except last (admin_id)
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if (mysqli_stmt_execute($stmt)) {
            // Update session data
            $_SESSION['admin_name'] = $admin_name;
            $_SESSION['admin_email'] = $admin_email;
            
            $message = 'Profile updated successfully';
            if (!empty($new_password)) {
                $message .= ' and password changed';
            }
            
            echo json_encode([
                'success' => true,
                'message' => $message
            ]);
        } else {
            throw new Exception('Database update failed: ' . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error updating profile: ' . $e->getMessage()
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
