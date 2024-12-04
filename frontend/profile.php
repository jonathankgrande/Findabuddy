<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require_once "../classes/dbh.classes.php";               // Database connection base class
require_once "../classes/profileinfo.classes.php";       // ProfileInfo base class
require_once "../classes/profileinfo-contr.classes.php"; // ProfileInfoContr class
require_once "../classes/profileinfo-view.classes.php";  // ProfileInfoView class
require_once "../classes/user-utility.classes.php";      // Utility functions

// Ensure the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Instantiate necessary classes
$profileInfo = new ProfileInfoView();
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
?>

<div class="container mx-auto pt-10">
    <!-- Informational Section -->
    <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
        <h2 class="text-blue-700 font-bold text-lg">Welcome to Your Profile!</h2>
        <p class="text-gray-700">
            Your profile is your way to share who you are and connect with others who share your interests. 
            Make sure to complete your details to help us find the best matches for you. Add a personal touch 
            and let others know what makes you unique!
        </p>
    </div>

    <!-- Main Layout -->
    <div class="flex space-x-4">
        <!-- Left Sidebar -->
        <div class="w-1/4 bg-white p-4 rounded-lg shadow-md">
            <div class="text-center">
                <img class="w-24 h-24 mx-auto rounded-full border-4 border-gray-300" src="https://via.placeholder.com/150" alt="User Profile Picture">
                <h2 class="text-xl font-semibold mt-4"><?php echo htmlspecialchars($_SESSION['userId']); ?></h2>
                <p class="text-gray-600">New York, NY</p>
            </div>
        </div>

        <!-- Main Content -->
        <main class="w-3/4 bg-white p-4 rounded-lg shadow-md space-y-6">
            <!-- Profile Information -->
            <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-blue-800">Your Profile Details</h3>
                <ul class="mt-4 space-y-2">
                    <li><strong>About:</strong> <?php $profileInfo->displayAbout($_SESSION['userId']); ?></li>
                    <li><strong>Title:</strong> <?php $profileInfo->displayTitle($_SESSION['userId']); ?></li>
                    <li><strong>Nickname:</strong> <?php $profileInfo->displayNickName($_SESSION['userId']); ?></li>
                    <li><strong>Gender:</strong> <?php $profileInfo->displayGender($_SESSION['userId']); ?></li>
                    <li><strong>Age:</strong> <?php $profileInfo->displayAge($_SESSION['userId']); ?></li>
                    <li><strong>Email:</strong> <?php $profileInfo->displayEmail($_SESSION['userId']); ?></li>
                    <li><strong>Phone Number:</strong> <?php $profileInfo->displayNumber($_SESSION['userId']); ?></li>
                    <li><strong>Borough:</strong> <?php $profileInfo->displayAddress($_SESSION['userId']); ?></li>
                </ul>
            </div>

            <!-- Customization Prompt -->
            <div class="bg-blue-50 p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-blue-800">Customize Your Experience</h3>
                <p class="text-gray-700 mt-2">
                    Add more details to your profile to make your experience personalized. 
                    Whether it’s your favorite hobbies, goals, or anything else, let others know what matters to you!
                </p>
            </div>
        </main>
    </div>
</div>

</body>
</html>
