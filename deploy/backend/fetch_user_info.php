<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db.php'; // Adjust path if needed

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

$response = [];

if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT username FROM user_admin WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $response['username'] = $row['username'];
        } else {
            $response['username'] = "User $user_id"; // Fallback if not found
        }
    } else {
        $response['error'] = "Error executing query: " . $stmt->error;
    }
    $stmt->close();
} else {
    $response['error'] = "Invalid user_id provided.";
}

echo json_encode($response);
$conn->close();
