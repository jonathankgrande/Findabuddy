<?php
require_once "classes/dbh.classes.php";

$dbh = new Dbh();
try {
    $connection = $dbh->connect();
    echo "Database connected successfully.";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
