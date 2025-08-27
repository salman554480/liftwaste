<?php
require_once('../parts/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all users from admins table
    $query = "SELECT admin_id, admin_name, admin_email, admin_role FROM admins ORDER BY admin_name";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = [
                'admin_id' => $row['admin_id'],
                'admin_name' => $row['admin_name'],
                'admin_email' => $row['admin_email'],
                'admin_role' => $row['admin_role']
            ];
        }
        
        echo json_encode([
            'success' => true,
            'users' => $users
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
        'message' => 'Invalid request method'
    ]);
}

mysqli_close($conn);
?>
