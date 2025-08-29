<?php
// Enable error reporting (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include DB connection (uses $conn)
require_once('parts/db.php');

// Set timezone to North Carolina Eastern Standard Time
date_default_timezone_set('America/New_York');


// Get raw POST body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Handle wrap_in_array = no
if (isset($data[0]) && is_array($data[0])) {
    $data = $data[0]; // unwrap if array
}

// Escape or default to NULL
$sender        = isset($data['sender']) ? "'" . mysqli_real_escape_string($conn, $data['sender']) . "'" : "NULL";
$sender_email  = isset($data['sender_email']) ? "'" . mysqli_real_escape_string($conn, $data['sender_email']) . "'" : "NULL";
$recipient     = isset($data['recipient']) ? "'" . mysqli_real_escape_string($conn, $data['recipient']) . "'" : "NULL";
$subject       = isset($data['subject']) ? "'" . mysqli_real_escape_string($conn, $data['subject']) . "'" : "NULL";
$body_text     = isset($data['body_text']) ? "'" . mysqli_real_escape_string($conn, $data['body_text']) . "'" : "NULL";
$body_html     = isset($data['body_html']) ? "'" . mysqli_real_escape_string($conn, $data['body_html']) . "'" : "NULL";

// Get current date and time in North Carolina Eastern Standard Time
$current_datetime = date('Y-m-d H:i:s');

// Build query
$sql = "
    INSERT INTO email (
        sender, status, received_at, 
        sender_email, recipient, subject, body_text, body_html
    ) VALUES (
        $sender, 'pending', '$current_datetime',
        $sender_email, $recipient, $subject, $body_text, $body_html
    )
";

// Execute query
if (mysqli_query($conn, $sql)) {
    echo json_encode([
        "success" => true,
        "insert_id" => mysqli_insert_id($conn)
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "error" => "Query failed: " . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>
