<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];  // Get the logged-in user's ID

// Fetch messages where the user is either the sender or the receiver
$query = "
    SELECT m.id, m.sender_id, m.receiver_id, m.content, m.timestamp, 
           u1.username AS sender_username, u2.username AS receiver_username
    FROM messages m
    JOIN user_admin u1 ON m.sender_id = u1.user_id
    JOIN user_admin u2 ON m.receiver_id = u2.user_id
    WHERE m.sender_id = ? OR m.receiver_id = ?
    ORDER BY m.timestamp ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are messages
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'sender_username' => $row['sender_username'],
        'receiver_username' => $row['receiver_username'],
        'content' => $row['content'],
        'timestamp' => $row['timestamp']
    ];
}

echo json_encode($messages);
?>
