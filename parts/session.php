<?php
session_start();

if (!isset($_SESSION['admin_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: login.php");
    exit;
} else {
    // Already logged in — fetch admin data
    if (isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id'];

        // Ensure $conn is available (included via top.php)
        $admin_query = "SELECT * FROM admins WHERE admin_id = '$admin_id' LIMIT 1";
        $admin_result = mysqli_query($conn, $admin_query);

        if ($admin_result && mysqli_num_rows($admin_result) == 1) {
            $admin_data = mysqli_fetch_assoc($admin_result);

            // Optionally store admin_data in session or global variable
            $admin_name = $admin_data['admin_name'];
            $admin_email = $admin_data['admin_email'];
            $admin_role = $admin_data['admin_role'];
        } else {
            // Admin ID in session is invalid, force logout
            session_destroy();
            header("Location: login.php");
            exit;
        }
    }
}
