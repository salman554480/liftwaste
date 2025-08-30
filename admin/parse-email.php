<?php
require_once('parts/db.php');

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die("Database connection failed: " . $conn->connect_error);
}

// Get incoming POST data
$from    = isset($_POST['from'])    ? $conn->real_escape_string($_POST['from'])    : '';
$to      = isset($_POST['to'])      ? $conn->real_escape_string($_POST['to'])      : '';
$subject = isset($_POST['subject']) ? $conn->real_escape_string($_POST['subject']) : '';
$text    = isset($_POST['text'])    ? $conn->real_escape_string($_POST['text'])    : '';
$html    = isset($_POST['html'])    ? $conn->real_escape_string($_POST['html'])    : '';
$headers = isset($_POST['headers']) ? $conn->real_escape_string($_POST['headers']) : '';

// Insert into DB
$sql = "INSERT INTO inbound_emails (sender, recipient, subject, body_text, body_html, headers)
        VALUES ('$from', '$to', '$subject', '$text', '$html', '$headers')";

if ($conn->query($sql) === TRUE) {
    echo "Email saved successfully.";
} else {
    http_response_code(500);
    echo "Error: " . $conn->error;
}

// Optional: Handle file attachments
foreach ($_FILES as $file) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = basename($file['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Optionally, you could insert file info into a separate attachments table
    }
}

$conn->close();
http_response_code(200);
?>
