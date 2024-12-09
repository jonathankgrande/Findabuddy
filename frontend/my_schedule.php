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

    // Database connection
    $conn = new mysqli("localhost", "csc350", "xampp", "findabuddy");
    if ($conn->connect_error) {
        die("<p class='text-red-500'>Database connection failed: " . $conn->connect_error . "</p>");
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action']) && $_POST['action'] === 'add_schedule') {
            if (isset($_POST['day'], $_POST['time'], $_POST['activity'])) {
                $day = $_POST['day'];
                $time = $_POST['time'];
                $activity = $_POST['activity'];

                $stmt = $conn->prepare("INSERT INTO workout (workout_date, workout_time, activity) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $day, $time, $activity);
                if ($stmt->execute()) {
                    echo "<p class='text-green-500 text-center'>Schedule saved successfully!</p>";
                } else {
                    echo "<p class='text-red-500 text-center'>Error saving schedule: " . $conn->error . "</p>";
                }
                $stmt->close();
            } else {
                echo "<p class='text-red-500 text-center'>Please select a day, time, and activity.</p>";
            }
        } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_schedule') {
            if (isset($_POST['schedule_id'])) {
                $schedule_id = $_POST['schedule_id'];
                $stmt = $conn->prepare("DELETE FROM workout WHERE workout_id = ?");
                $stmt->bind_param("i", $schedule_id);
                if ($stmt->execute()) {
                    echo "<p class='text-green-500 text-center'>Schedule deleted successfully!</p>";
                } else {
                    echo "<p class='text-red-500 text-center'>Error deleting schedule: " . $conn->error . "</p>";
                }
                $stmt->close();
            }
        }
    }

    ?>
    
    <?php
    // Mapping of short weekday names to full names
    $weekdays = [
        "Mo" => "Monday",
        "Tu" => "Tuesday",
        "We" => "Wednesday",
        "Th" => "Thursday",
        "Fr" => "Friday",
        "Sa" => "Saturday",
        "Su" => "Sunday"
    ];
    ?>


    <div class="container mx-auto mt-12">
        <!-- Informational Section -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">Set Your Availability</h2>
            <p class="text-gray-700">
                Use this schedule to set your workout availability. 
                This will help others find you based on your preferred times, days, and activities. 
                Choose the days you are available, specify your preferred time slots, 
                and select an activity (Run, Walk, or Bike).
            </p>
        </div>

        <!-- Schedule Form -->
        <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-lg">
            <input type="hidden" name="action" value="add_schedule">
            <div class="mb-4">
                <label for="day" class="block text-gray-700 font-medium mb-2">Choose a Day:</label>
                <select id="day" name="day" class="block w-full border border-gray-300 px-4 py-2 rounded">
                    <option value="">Select a Day</option>
                    <option value="Mo">Monday</option>
                    <option value="Tu">Tuesday</option>
                    <option value="We">Wednesday</option>
                    <option value="Th">Thursday</option>
                    <option value="Fr">Friday</option>
                    <option value="Sa">Saturday</option>
                    <option value="Su">Sunday</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="time" class="block text-gray-700 font-medium mb-2">Preferred Time:</label>
                <input type="time" id="time" name="time" class="block w-full border border-gray-300 px-4 py-2 rounded">
            </div>
            <div class="mb-4">
                <label for="activity" class="block text-gray-700 font-medium mb-2">Choose an Activity:</label>
                <select id="activity" name="activity" class="block w-full border border-gray-300 px-4 py-2 rounded">
                    <option value="">Select an Activity</option>
                    <option value="Run">Run</option>
                    <option value="Walk">Walk</option>
                    <option value="Bike">Bike</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none block mx-auto">
                Submit
            </button>
        </form>

        <!-- Display Existing Schedules -->
        <div class="mt-12">
            <h3 class="text-lg font-bold text-gray-700">Your Current Schedule</h3>
            <table class="w-full table-auto border-collapse mt-4">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border border-gray-300 px-4 py-2">Day</th>
                        <th class="border border-gray-300 px-4 py-2">Time</th>
                        <th class="border border-gray-300 px-4 py-2">Activity</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT workout_id, workout_date, workout_time, activity FROM workout ORDER BY FIELD(workout_date, 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su')");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $full_day_name = $weekdays[$row['workout_date']];
                            echo "<tr>
                                    <td class='border border-gray-300 px-4 py-2'>{$full_day_name}</td>
                                    <td class='border border-gray-300 px-4 py-2'>{$row['workout_time']}</td>
                                    <td class='border border-gray-300 px-4 py-2'>{$row['activity']}</td>
                                    <td class='border border-gray-300 px-4 py-2 text-center'>
                                        <form method='POST' action='' style='display:inline-block;'>
                                            <input type='hidden' name='action' value='delete_schedule'>
                                            <input type='hidden' name='schedule_id' value='{$row['workout_id']}'>
                                            <button type='submit' class='bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center text-gray-500'>No schedules found.</td></tr>";
                    }
                    ?>
                </tbody>
        </div>
    </div>

</body>
</html>
