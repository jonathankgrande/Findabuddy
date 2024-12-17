<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../backend/db.php'; // Database connection
require_once '../backend/fetchMatches.php'; // Include the search function

$results = ''; // To store search results
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $results = fetchMatches($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find-A-Buddy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    <?php
    $pageHeader = "Find-A-Buddy";
    if (file_exists('../includes/navbar.php')) {
        include '../includes/navbar.php';
    } else {
        echo "<p class='text-red-500'>Error: Navbar file not found.</p>";
    }
    ?>

    <div class="container mx-auto pt-10">
        <!-- Informational Section -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">Find Your Buddy</h2>
            <p class="text-gray-700">
                Use this feature to search for people who match your availability, interests, and preferred days.
            </p>
        </div>

        <!-- Search Form -->
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-center text-blue-800 mb-6">Search For Matches</h1>
            <form method="post" action="" class="space-y-6">
                <div class="grid grid-cols-3 gap-4">
                    <!-- Workout -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Workout</label>
                        <select name="exercise" required class="w-full border rounded px-3 py-2">
                            <option value="blank">Select Workout</option>
                            <option value="Run">Run</option>
                            <option value="Bike">Bike</option>
                            <option value="Walk">Walk</option>
                        </select>
                    </div>
                    <!-- Days -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Days</label>
                        <div>
                            <?php
                            $days = ['Mo' => 'Monday', 'Tu' => 'Tuesday', 'We' => 'Wednesday', 'Th' => 'Thursday', 'Fr' => 'Friday', 'Sa' => 'Saturday', 'Su' => 'Sunday'];
                            foreach ($days as $short => $day) {
                                echo "<label class='flex items-center'><input type='checkbox' name='days[]' value='$short' class='mr-2'>$day</label>";
                            }
                            ?>
                        </div>
                    </div>
                    <!-- Time -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Time</label>
                        <select name="time" required class="w-full border rounded px-3 py-2">
                            <option value="blank">Select Time</option>
                            <?php
                            for ($i = 6; $i <= 22; $i++) {
                                $time = sprintf("%02d:00:00", $i);
                                $display = date("g A", strtotime($time));
                                echo "<option value='$time'>$display</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Search</button>
            </form>
        </div>

        <!-- Results Section -->
        <?php if (!empty($results)): ?>
            <div class="mt-8">
                <?php echo $results; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
