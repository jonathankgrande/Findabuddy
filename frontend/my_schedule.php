<?php
session_start();

// Verify user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$conn = new mysqli("localhost", "csc350", "xampp", "findabuddy");
if ($conn->connect_error) {
    die("<p class='text-red-500'>Database connection failed: " . $conn->connect_error . "</p>");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'add_schedule') {
        if (!empty($_POST['day']) && !empty($_POST['time']) && !empty($_POST['activity'])) {
            $day = $_POST['day'];
            $time = $_POST['time'];
            $activity = $_POST['activity'];
            $user_id = $_SESSION['user_id'];

            $stmt = $conn->prepare("INSERT INTO workout (workout_date, workout_time, activity, user_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $day, $time, $activity, $user_id);

            if ($stmt->execute()) {
                echo "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4'>
                        Schedule created successfully!
                      </div>";
            } else {
                echo "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4'>
                        Error adding schedule: " . $conn->error . "
                      </div>";
            }
            $stmt->close();
        } else {
            echo "<p class='text-red-500 text-center'>Please select a day, time, and activity.</p>";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_schedule') {
        if (isset($_POST['schedule_id'])) {
            $schedule_id = $_POST['schedule_id'];
            $user_id = $_SESSION['user_id'];

            $stmt = $conn->prepare("DELETE FROM workout WHERE workout_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $schedule_id, $user_id);

            if ($stmt->execute()) {
                echo "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4'>
                        Schedule deleted successfully!
                      </div>";
            } else {
                echo "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4'>
                        Error deleting schedule: " . $conn->error . "
                      </div>";
            }
            $stmt->close();
        }
    }
}

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
        echo "<p class='text-red-500'>Error: Navbar not found.</p>";
    }
    ?>

    <div class="container mx-auto mt-12">
        <!-- Informational Section -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">Set Your Availability</h2>
            <p class="text-gray-700">
                Use this schedule to set your workout availability. 
                This will help others find you based on your preferred times, days, and activities.
            </p>
        </div>

        <!-- Schedule Form -->
        <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <input type="hidden" name="action" value="add_schedule">
            <div class="mb-4">
                <label for="day" class="block text-gray-700 font-medium mb-2">Choose a Day:</label>
                <select id="day" name="day" class="block w-full border px-4 py-2 rounded">
                    <option value="">Select a Day</option>
                    <?php foreach ($weekdays as $key => $value): ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="time" class="block text-gray-700 font-medium mb-2">Preferred Time:</label>
                <input type="time" id="time" name="time" class="block w-full border px-4 py-2 rounded">
            </div>
            <div class="mb-4">
                <label for="activity" class="block text-gray-700 font-medium mb-2">Choose an Activity:</label>
                <select id="activity" name="activity" class="block w-full border px-4 py-2 rounded">
                    <option value="">Select an Activity</option>
                    <option value="Run">Run</option>
                    <option value="Walk">Walk</option>
                    <option value="Bike">Bike</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 block mx-auto">
                Submit
            </button>
        </form>

 <!-- Display User-Specific Schedules -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-lg font-bold text-gray-700 mb-4">Your Current Schedule</h3>
    <?php
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT workout_id, workout_date, workout_time, activity 
                            FROM workout 
                            WHERE user_id = ? 
                            ORDER BY FIELD(workout_date, 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su')");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='space-y-4'>";
        while ($row = $result->fetch_assoc()) {
            $full_day_name = $weekdays[$row['workout_date']];
            $time = htmlspecialchars($row['workout_time']); // Display workout time
            $activity = htmlspecialchars($row['activity']);
            echo "
                <div class='flex justify-between items-center border-b pb-2'>
                    <div>
                        <p class='font-medium text-gray-700'>{$full_day_name} - {$activity}</p>
                        <p class='text-gray-600'>Time: {$time}</p>
                    </div>
                    <form method='POST' action=''>
                        <input type='hidden' name='action' value='delete_schedule'>
                        <input type='hidden' name='schedule_id' value='{$row['workout_id']}'>
                        <button type='submit' class='bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600'>
                            Delete
                        </button>
                    </form>
                </div>
            ";
        }
        echo "</div>";
    } else {
        echo "<p class='text-gray-500'>No schedules found. Add one using the form above.</p>";
    }
    $stmt->close();
    ?>
</div>
    </div>
</body>
</html>
