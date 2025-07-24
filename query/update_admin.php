<?php
require_once('../parts/db.php');

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_id'])) {
    $id       = intval($_POST['admin_id']);
    $name     = mysqli_real_escape_string($conn, $_POST['admin_name']);
    $email    = mysqli_real_escape_string($conn, $_POST['admin_email']);
    $contact  = mysqli_real_escape_string($conn, $_POST['admin_contact']);
    $address  = mysqli_real_escape_string($conn, $_POST['admin_address']);
    $role     = mysqli_real_escape_string($conn, $_POST['admin_role']);
    $status   = mysqli_real_escape_string($conn, $_POST['admin_status']);
    $password   = mysqli_real_escape_string($conn, $_POST['admin_password']);
    // $password = isset($_POST['admin_password']) && !empty(trim($_POST['admin_password']))
    //             ? md5(trim($_POST['admin_password']))
    //             : '';

    $sql = "UPDATE admins SET
              admin_name = '$name',
              admin_email = '$email',
              admin_contact = '$contact',
              admin_address = '$address',
              admin_role = '$role',
              admin_status = '$status'";

    if ($password !== '') {
        $sql .= ", admin_password = '$password'";
    }

    $sql .= " WHERE admin_id = $id";

    if (mysqli_query($conn, $sql)) {
        $response = ['success' => true, 'message' => 'Admin updated successfully'];
    } else {
        $response['message'] = 'Update failed: ' . mysqli_error($conn);
    }
}

echo json_encode($response);