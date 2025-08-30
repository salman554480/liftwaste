<?php
// Enable error reporting (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set response header
header('Content-Type: application/json');

// Set timezone to North Carolina Eastern Standard Time
date_default_timezone_set('America/New_York');

require_once('parts/db.php');

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit;
}

// Read POST body (JSON)
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Check for JSON parse errors
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid JSON input.'
    ]);
    exit;
}

$message = isset($data['message']) ? $data['message'] : null;

$sender = null;
$msg = null;

// Match sender and message from "From: {sender}\n{msg}"
if ($message && preg_match('/From:\s*(.*?)\n(.*)/s', $message, $matches)) {
    $sender = trim($matches[1]);
    $msg = trim($matches[2]);
}

// Validate extracted data
if (!$sender || !$msg) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Could not extract "sender" and "msg" from the input.'
    ]);
    exit;
}

// Save to text file
$log_entry = "Sender: $sender\nMessage: $msg\n---\n";
file_put_contents('messages_log.txt', $log_entry, FILE_APPEND);

// Escape input to prevent SQL injection
$sender_escaped = mysqli_real_escape_string($conn, $sender);
$msg_escaped = mysqli_real_escape_string($conn, $msg);

// Get current date and time in North Carolina Eastern Standard Time
$current_datetime = date('Y-m-d H:i:s');

// Insert into database
$sql = "INSERT INTO email (sender, body_html, received_at)
        VALUES ('$sender_escaped', '$msg_escaped', '$current_datetime')";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode([
        'status' => 'success',
        'message' => 'SMS data saved successfully.',
        'data' => [
            'sender' => $sender,
            'msg' => $msg
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database insert failed: ' . mysqli_error($conn)
    ]);
}

// Close connection
$conn->close();
?>
