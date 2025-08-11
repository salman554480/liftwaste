<?php
require_once('../parts/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assign_id = isset($_POST['assign_id']) ? intval($_POST['assign_id']) : 0;
    $new_status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($assign_id > 0 && in_array($new_status, ['in_progress', 'completed'])) {
        $now = date('Y-m-d H:i:s');

        $update = "UPDATE assign SET assign_status = '$new_status', updated_at = '$now' WHERE assign_id = $assign_id";

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
