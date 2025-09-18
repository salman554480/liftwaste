<?php
// Set timezone to North Carolina Eastern Time
date_default_timezone_set('America/New_York');

require_once('../parts/db.php');
require_once('../parts/session.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_id = isset($_POST['email_id']) ? intval($_POST['email_id']) : 0;
    $new_status = isset($_POST['status']) ? $_POST['status'] : '';

    // Check if user is logged in
    if (!isLoggedIn()) {
        echo json_encode([
            'success' => false,
            'message' => 'Authentication required. Please login.'
        ]);
        exit();
    }

    // Use session admin_id
    $admin_id = $_SESSION['admin_id'];

    if ($email_id > 0) {

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