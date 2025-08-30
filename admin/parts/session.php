<?php
// No session required - dashboard can be accessed by anyone
// Set default values for compatibility
session_start();
if(!isset($_SESSION['admin_id'])){
    header('Location: login.php');
    exit();
}else{
    $admin_id = $_SESSION['admin_id'];
  
    $sql = "SELECT * FROM admins WHERE admin_id = '$admin_id'";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);
    $admin_name = $admin['admin_name'];
    $admin_role = $admin['admin_role'];
    $admin_email = $admin['admin_email'];
}
?>
