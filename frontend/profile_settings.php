<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <?php
    $pageHeader = "Settings";

    if (file_exists('../includes/navbar.php')) {
        include '../includes/navbar.php';
    } else {
        echo "<p class='text-red-500 text-center'>Error: File not found.</p>";
    }
    ?>

    <div class="container mx-auto pt-10">
        <!-- Informational Section -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">Settings</h2>
            <p class="text-gray-700">
                Manage your profile and customize your preferences to enhance your experience. 
                Update your personal information and tell us about yourself! 
                Keep your details up to date to ensure accurate matches and better connections!
            </p>
        </div>

        <!-- Main Content -->
        <main class="w-3/4 bg-white p-6 rounded-lg shadow-md mx-auto">
            <!-- Profile Information -->
            <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-blue-800 mb-4">Edit Your Profile</h3>
                <form action="profile.php" method="post" class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-800">Title:</label>
                        <input type="text" id="title" name="title" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Nickname -->
                    <div>
                        <label for="nickname" class="block text-sm font-medium text-gray-800">Nickname:</label>
                        <input type="text" id="nickname" name="nickname" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-800">Gender:</label>
                        <select id="gender" name="gender" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="non_binary">Non-binary</option>
                            <option value="prefer_not_to_say">Prefer not to say</option>
                        </select>
                    </div>

                    <!-- Age -->
                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-800">Age:</label>
                        <input type="number" id="age" name="age" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-800">Email:</label>
                        <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-800">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Borough -->
                    <div>
                        <label for="borough" class="block text-sm font-medium text-gray-800">Borough:</label>
                        <input type="text" id="borough" name="borough" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
