<?php
// Start the session to access session variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans text-gray-800">
    <!--  Nav Bar -->
    <nav class="bg-white shadow-md w-full z-10">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-800"><?php echo htmlspecialchars($pageHeader); ?> </h1>
            <ul class="flex space-x-8">
                <li><a href="homepage.php" class="text-gray-800 hover:text-blue-500">Homepage</a></li>
                <li><a href="find_a_buddy.php" class="text-gray-800 hover:text-blue-500">Find-A-Buddy</a></li>
                <li><a href="messages.php" class="text-gray-800 hover:text-blue-500">Messages</a></li>
                <li><a href="my_schedule.php" class="text-gray-800 hover:text-blue-500">My Schedule</a></li>
                <li><a href="profile.php" class="text-gray-800 hover:text-blue-500">Profile</a></li>
                <li><a href="profile_settings.php" class="text-gray-800 hover:text-blue-500">Settings</a></li>

                <!-- Display the username if logged in -->
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="text-blue-800">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></li>
                    <li><a href="../backend/logout.php" class="text-red-500 hover:text-red-700">Logout</a></li>
                <?php else: ?>
                    <li><a href="../frontend/login.php" class="text-blue-500 hover:text-blue-700">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>   
</body>
</html>
