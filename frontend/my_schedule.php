<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule</title>
    <script src="toggleDay.js"></script>
</head> 
<body>
<?php
$pageHeader = "My Schedule";
if (file_exists('../includes/navbar.php')) {
    include '../includes/navbar.php';
} else {
    echo "<p> Error: File not found. </p>";
}
?>

    <div class = "container mx-auto mt-24">
        <?php 
        if (file_exists('../backend/preferredworkout.php')){
            include '../backend/preferredworkout.php'; 
        } else {
            echo "<p> Error; File not found. </p>";
        }
        ?>
    </div>
</body>
