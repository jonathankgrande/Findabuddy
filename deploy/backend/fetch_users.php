<?php
include 'db.php'; // Include database connection

header('Content-Type: application/json');

// Fetch all users from the database
$query = "SELECT user_id, username FROM user_admin";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(["error" => "Failed to fetch users"]);
    exit;
}

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Return the list of users as JSON
echo json_encode($users);
?>
