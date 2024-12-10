<?php
$host = "localhost";    // Database host
$username = "csc350";     // Database username
$password = "xampp";         // Database password
$database = "findabuddy"; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
