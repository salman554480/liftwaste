<?php
require_once('../parts/db.php'); // adjust if your DB connection file is named differently
require_once('../parts/session.php'); // adjust if your DB connection file is named differently

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email_id'])) {
    $email_id = intval($_POST['email_id']);
    $assign_status = 'assigned';
    $now = date('Y-m-d H:i:s');


    //update email status
    $update_email = "UPDATE email SET status = 'assigned', admin_id = '$admin_id', updated_at = '$now' WHERE id = '$email_id'";
    mysqli_query($conn, $update_email);

    echo json_encode([
        'success' => true,
        'message' => 'Email assigned successfully!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
