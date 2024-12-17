<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<pre>';
    print_r($_FILES);
    echo '</pre>';

    $uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/team-blue/uploads/';
    $filePath = $uploadDir . basename($_FILES['profile_image']['name']);

    if (!is_dir($uploadDir)) {
        die("Uploads directory does not exist.");
    }

    if (!is_writable($uploadDir)) {
        die("Uploads directory is not writable.");
    }

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $filePath)) {
        echo "File uploaded successfully to: " . $filePath;
    } else {
        echo "Failed to move uploaded file.";
    }
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="profile_image">
    <button type="submit">Upload</button>
</form>

