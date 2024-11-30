<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<?php
$pageHeader = "Profile";
    if (file_exists('../includes/navbar.php')){
        include '../includes/navbar.php';
        } else {
            echo "<p> Error: File not found. </p>";
        }
?>