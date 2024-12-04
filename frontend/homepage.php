<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<?php
$pageHeader = "Homepage";

    if (file_exists('../includes/navbar.php')){
        include '../includes/navbar.php';
        } else {
            echo "<p> Error: File not found. </p>";
        }
?>    
    <div class="container mx-auto pt-10">
        <!-- Welcome Section -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">Welcome to Your Homepage!</h2>
            <p class="text-gray-700">
                This is your personal space to manage your fitness journey and connections. 
                From planning your schedule to finding new workout buddies, everything you need is just a click away.
                Explore your clubs, suggested matches, and activities to stay motivated and connected!
            </p>
    </div>
    <!-- Main Layout what keeps the layout intact -->
    <div class="flex pt-30 container mx-auto px-4 space-x-4">
        <!-- Left Sidebar -->
        <div class="w-1/4 bg-white p-4 rounded-lg shadow-md">
            <div class="text-center">
                <img class="w-24 h-24 mx-auto rounded-full border-4 border-gray-300" src="https://photo.com/150" alt="User Profile Picture">
                <h2 class="text-xl font-semibold mt-4">Jonathan Grande</h2>
                <p class="text-gray-600">Queens, NY</p>
            </div>
        </div>

        <!-- Main Content -->
        <main class="w-1/2 bg-white p-4 rounded-lg shadow-md space-y-6">
            <!--Schedule Section -->
            <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                <a href="#" h2 class="text-lg font-bold text-blue-800 mb-4">My Schedule</a>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Monday - Run with Michael</span>
                        <span class="bg-gray-200 text-blue-800 px-3 py-1 rounded-full">6 miles</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Tuesday - Biking with Daniela</span>
                        <span class="bg-gray-200 text-blue-800 px-3 py-1 rounded-full">10 miles</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Wednesday - Walk with Rachel</span>
                        <span class="bg-gray-200 text-blue-800 px-3 py-1 rounded-full">2 miles</span>
                    </div>
                </div>
            </div>
        </main>

        <!-- Right Sidebar -->
        <aside class="w-1/4 space-y-4">
            <!-- Find A Buddy Section -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-800">Find a Buddy!</h3>
                <p class="text-gray-600 mt-2">Find a buddy to exercise with!</p>
                <a href="find_a_buddy.php" class="mt-2 block bg-blue-500 text-white text-center py-2 rounded-lg hover:bg-blue-600">Find Your Buddy</a>
            </div>

            <!-- Clubs Section, low priority just threw it in there to fill in space -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-800">Your Clubs</h3>
                <div class="mt-4 flex space-x-4">
                    <img class="w-10 h-10 rounded-md" src="https://via.placeholder.com/40" alt="Club 1">
                    <img class="w-10 h-10 rounded-md" src="https://via.placeholder.com/40" alt="Club 2">
                    <img class="w-10 h-10 rounded-md" src="https://via.placeholder.com/40" alt="Club 3">
                </div>
                <a href="#" class="mt-4 block text-blue-500 text-sm hover:underline">View All Clubs</a>
            </div>

            <!-- Suggested Friends Section Also Low Priority -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-800">Suggested Matches</h3>
                <div class="mt-4">
                    <div class="flex items-center space-x-4">
                        <img class="w-10 h-10 rounded-full" src="https://via.placeholder.com/40" alt="Friend 1">
                        <div>
                            <p class="font-semibold">Bert Moreno</p>
                            <button class="text-blue-500 text-sm hover:underline">Message</button>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 mt-4">
                        <img class="w-10 h-10 rounded-full" src="https://via.placeholder.com/40" alt="Friend 2">
                        <div>
                            <p class="font-semibold">Minru H</p>
                            <button class="text-blue-500 text-sm hover:underline">Message</button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</body>

</html>
