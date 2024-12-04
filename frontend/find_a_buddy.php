<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search For Matches</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <?php
    $pageHeader = "Find-A-Buddy";

    if (file_exists('../includes/navbar.php')) {
        include '../includes/navbar.php';
    } else {
        echo "<p class='text-red-500'>Error: File not found.</p>";
    }
    ?>

    <div class="container mx-auto pt-10">
        <!-- Informational Section -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">Find Your Buddy</h2>
            <p class="text-gray-700">
                Use this feature to search for people who match your availability, interests, and preferred days. 
                Whether you're into running, biking, or walking, this tool connects you with others who share your schedule 
                and activity preferences. Simply select your workout type, availability, and time to find your perfect match!
            </p>
        </div>

        <!-- Search Form -->
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-center text-blue-800 mb-6">Search For Matches</h1>
            <form action="searchTemplate.php" method="post" class="space-y-6">
                <!-- Table Header -->
                <div class="grid grid-cols-3 gap-4 bg-gray-200 p-4 rounded-md">
                    <div class="text-center font-semibold text-gray-700">Workout</div>
                    <div class="text-center font-semibold text-gray-700">Days Available</div>
                    <div class="text-center font-semibold text-gray-700">Time</div>
                </div>

                <!-- Form Inputs -->
                <div class="grid grid-cols-3 gap-4 bg-gray-100 p-4 rounded-md">
                    <!-- Workout Selection -->
                    <div class="flex flex-col">
                        <label for="exercise" class="block text-gray-700 font-medium mb-2">Select Workout</label>
                        <select name="exercise" id="exercise" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
                            <option value="blank" default>Select Workout</option>
                            <option value="bike">Biking</option>
                            <option value="run">Running</option>
                            <option value="walk">Walking</option>
                        </select>
                    </div>

                    <!-- Days Available -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Select Days</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="days[]" value="Mo" class="mr-2">
                                Monday
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days[]" value="Tu" class="mr-2">
                                Tuesday
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days[]" value="We" class="mr-2">
                                Wednesday
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days[]" value="Th" class="mr-2">
                                Thursday
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days[]" value="Fr" class="mr-2">
                                Friday
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days[]" value="Sa" class="mr-2">
                                Saturday
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="days[]" value="Su" class="mr-2">
                                Sunday
                            </label>
                        </div>
                    </div>

                    <!-- Time Selection -->
                    <div class="flex flex-col">
                        <label for="time" class="block text-gray-700 font-medium mb-2">Select Time</label>
                        <select name="time" id="time" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
                            <option value="blank" default>Select Time</option>
                            <option value="06:00:00">6 AM</option>
                            <option value="07:00:00">7 AM</option>
                            <option value="08:00:00">8 AM</option>
                            <option value="09:00:00">9 AM</option>
                            <option value="10:00:00">10 AM</option>
                            <option value="11:00:00">11 AM</option>
                            <option value="12:00:00">12 PM</option>
                            <option value="13:00:00">1 PM</option>
                            <option value="14:00:00">2 PM</option>
                            <option value="15:00:00">3 PM</option>
                            <option value="16:00:00">4 PM</option>
                            <option value="17:00:00">5 PM</option>
                            <option value="18:00:00">6 PM</option>
                            <option value="19:00:00">7 PM</option>
                            <option value="20:00:00">8 PM</option>
                            <option value="21:00:00">9 PM</option>
                            <option value="22:00:00">10 PM</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit"
                        class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition">
                        Search For Match
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
