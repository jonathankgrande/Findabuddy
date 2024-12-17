<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id']; // Retrieve user ID from session
echo "DEBUG: User ID is: " . $userId; // Output to confirm the value
