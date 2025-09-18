<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);

// Set default values for compatibility
$admin_id = $is_logged_in ? $_SESSION['admin_id'] : 0;
$admin_role = $is_logged_in ? $_SESSION['admin_role'] : 'guest';
$admin_name = $is_logged_in ? $_SESSION['admin_name'] : 'Guest User';
$admin_email = $is_logged_in ? $_SESSION['admin_email'] : 'guest@example.com';

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Function to require login (redirect to login page if not logged in)
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to get current user data
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['admin_id'],
            'name' => $_SESSION['admin_name'],
            'email' => $_SESSION['admin_email'],
            'role' => $_SESSION['admin_role']
        ];
    }
    return null;
}
?>
