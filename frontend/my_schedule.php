<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule</title>
    <script src="../js/toggleDay.js"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <?php
    $pageHeader = "My Schedule";
    if (file_exists('../includes/navbar.php')) {
        include '../includes/navbar.php';
    } else {
        echo "<p>Error: File not found.</p>";
    }
    ?>

    <div class="container mx-auto mt-24">
        <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border px-4 py-2">Day</th>
                        <th class="border px-4 py-2">Workout?</th>
                        <th class="border px-4 py-2">Preferred Time</th>
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
            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 mt-4 rounded-lg hover:bg-blue-700 focus:outline-none block mx-auto">
                Submit
            </button>
        </form>
    </div>

</body>
</html>