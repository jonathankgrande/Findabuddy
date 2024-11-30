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
                <li><button class="text-red-500 hover:text-red-700">Logout</button></li>
            </ul>
        </div>
    </nav>   