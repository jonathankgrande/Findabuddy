<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch messages grouped by other users
$query = "
    SELECT m.id, m.sender_id, m.receiver_id, m.content, m.timestamp, 
           IF(m.sender_id = ?, u2.username, u1.username) AS username,
           IF(m.sender_id = ?, true, false) AS is_sent_by_user
    FROM messages m
    JOIN user_admin u1 ON m.sender_id = u1.user_id
    JOIN user_admin u2 ON m.receiver_id = u2.user_id
    WHERE m.sender_id = ? OR m.receiver_id = ?
    ORDER BY m.timestamp ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$threads = [];
while ($row = $result->fetch_assoc()) {
    $other_user_id = $row['sender_id'] == $user_id ? $row['receiver_id'] : $row['sender_id'];

    if (!isset($threads[$other_user_id])) {
        $threads[$other_user_id] = [
            'username' => $row['username'],
            'messages' => []
        ];
    }

    $threads[$other_user_id]['messages'][] = [
        'content' => $row['content'],
        'timestamp' => $row['timestamp'],
        'is_sent_by_user' => $row['is_sent_by_user']
    ];
}

echo json_encode($threads);
?>
