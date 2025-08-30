<?php
require_once('../parts/db.php');

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM admins WHERE admin_id = $id";

    if (mysqli_query($conn, $sql)) {
        $response = ['success' => true, 'message' => 'Admin deleted successfully'];
    } else {
        $response['message'] = 'Delete failed: ' . mysqli_error($conn);
    }
}

echo json_encode($response);
