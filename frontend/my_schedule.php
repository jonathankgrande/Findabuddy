<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule</title>
    <script src="../js/toggleDay.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <?php
    $pageHeader = "My Schedule";
    if (file_exists('../includes/navbar.php')) {
        include '../includes/navbar.php';
    } else {
        echo "<p class='text-red-500'>Error: File not found.</p>";
    }
    ?>

    <div class="container mx-auto mt-12">
        <!-- Informational Section -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">Set Your Availability</h2>
            <p class="text-gray-700">
                Use this schedule to set your workout availability. 
                This will help others find you based on your preferred times and days. 
                Choose the days you are available, indicate whether you plan to work out, 
                and specify your preferred time slots. Let’s help you connect with others
                who share your schedule!
            </p>
        </div>

        <!-- Schedule Form -->
        <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-lg">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border border-gray-300 px-4 py-2">Day</th>
                        <th class="border border-gray-300 px-4 py-2">Workout?</th>
                        <th class="border border-gray-300 px-4 py-2">Preferred Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (file_exists('../backend/preferredworkout.php')) {
                        include '../backend/preferredworkout.php'; 
                    } else {
                        echo "<tr><td colspan='3' class='text-center text-red-500'>Error: File not found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 mt-6 rounded-lg hover:bg-blue-700 focus:outline-none block mx-auto">
                Submit
            </button>
        </form>
    </div>

</body>
</html>
