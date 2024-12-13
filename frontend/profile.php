<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require_once "../classes/dbh.classes.php";               // Database connection base class
require_once "../classes/profileinfo.classes.php";       // ProfileInfo base class
require_once "../classes/profileinfo-view.classes.php";  // ProfileInfoView class

// Ensure the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Instantiate the ProfileInfoView class
$profileInfo = new ProfileInfoView();
$userData = $profileInfo->getProfileInfo($_SESSION['userId']); // Fetch user data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

<?php
$pageHeader = "Profile";
if (file_exists('../includes/navbar.php')) {
    include '../includes/navbar.php';
} else {
    echo "<p class='text-red-500'>Error: Navbar file not found.</p>";
}

$profileInfo = new ProfileInfoView();
$userData = $profileInfo->fetchProfileInfo($_SESSION['userId']); // Fetch user data
?>

<div class="container mx-auto pt-10">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-blue-800 text-2xl font-bold">Your Profile</h2>
        <ul class="mt-4 space-y-2">
            <li><strong>About:</strong> <?php echo htmlspecialchars($userData['about'] ?? 'No information available.'); ?></li>
            <li><strong>Title:</strong> <?php echo htmlspecialchars($userData['title'] ?? 'No title available.'); ?></li>
            <li><strong>Nickname:</strong> <?php echo htmlspecialchars($userData['nick_name'] ?? 'No nickname available.'); ?></li>
            <li><strong>Gender:</strong> <?php echo htmlspecialchars($userData['gender'] ?? 'No gender information available.'); ?></li>
            <li><strong>Age:</strong> <?php echo htmlspecialchars($userData['age'] ?? 'No age information available.'); ?></li>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($userData['email'] ?? 'No email information available.'); ?></li>
            <li><strong>Phone Number:</strong> <?php echo htmlspecialchars($userData['phone_number'] ?? 'No phone number available.'); ?></li>
            <li><strong>Borough:</strong> <?php echo htmlspecialchars($userData['user_address'] ?? 'No borough available.'); ?></li>
        </ul>
    </div>
</div>

</body>
</html>
