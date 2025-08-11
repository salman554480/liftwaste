<?php
require_once('../parts/db.php'); // adjust if your DB connection file is named differently
require_once('../parts/session.php'); // adjust if your DB connection file is named differently

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email_id'])) {
    $email_id = intval($_POST['email_id']);
    $assign_status = 'assigned';
    $now = date('Y-m-d H:i:s');

    $insert = "INSERT INTO assign (email_id, admin_id, assign_status, created_at)
               VALUES ('$email_id', '$admin_id', '$assign_status', '$now')";

    if (mysqli_query($conn, $insert)) {

        //update email status
        $update_email = "UPDATE email SET status = 'assigned' WHERE id = '$email_id'";
        mysqli_query($conn, $update_email);

        echo json_encode([
            'success' => true,
            'message' => 'Email assigned successfully!'
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
        'message' => 'Invalid request'
    ]);
}
