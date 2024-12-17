<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to send a message.']);
    exit;
}

// Get sender ID from session
$sender_id = $_SESSION['user_id'];

// Retrieve and validate POST data
$data = json_decode(file_get_contents('php://input'), true);
$receiver_id = $data['receiver_id'] ?? null;
$content = $data['content'] ?? '';

if (!$receiver_id || !$content) {
    echo json_encode(['success' => false, 'message' => 'Invalid receiver or message content.']);
    exit;
}

// Database connection
require 'db.php';

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $sender_id, $receiver_id, $content);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message.']);
}

$stmt->close();
$conn->close();
?>