<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Include the database connection class
require_once "../classes/dbh.classes.php";

// Initialize database connection
$dbh = new Dbh();
$conn = $dbh->connect();

// Fetch user's schedule using PDO
$userId = $_SESSION['user_id'];
$query = "SELECT workout_date, workout_time, activity 
          FROM workout 
          WHERE user_id = :userId 
          ORDER BY FIELD(workout_date, 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su')";

$stmt = $conn->prepare($query);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$scheduleResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mapping short days to full names
$daysMap = [
    "Mo" => "Monday",
    "Tu" => "Tuesday",
    "We" => "Wednesday",
    "Th" => "Thursday",
    "Fr" => "Friday",
    "Sa" => "Saturday",
    "Su" => "Sunday"
];

// Include navbar
$pageHeader = "Homepage";
if (file_exists('../includes/navbar.php')) {
    include '../includes/navbar.php';
} else {
    echo "<p>Error: Navbar not found.</p>";
}
?>

<div class="container mx-auto pt-10">
    <!-- Welcome Section -->
    <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
        <h2 class="text-blue-700 font-bold text-lg">Welcome to Your Homepage!</h2>
        <p class="text-gray-700">
            This is your personal space to manage your fitness journey and connections. 
            From planning your schedule to finding new workout buddies, everything you need is just a click away.
        </p>
    </div>

    <!-- Main Layout -->
    <div class="flex container mx-auto px-4 space-x-4">
        <!-- Left Sidebar -->
        <div class="w-1/4 bg-white p-4 rounded-lg shadow-md">
            <div class="text-center">
                <img class="w-24 h-24 mx-auto rounded-full border-4 border-gray-300" src="https://photo.com/150" alt="User Profile Picture">
                <h2 class="text-xl font-semibold mt-4"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <p class="text-gray-600">Queens, NY</p>
            </div>
        </div>

        <!-- Main Content -->
        <main class="w-1/2 bg-white p-4 rounded-lg shadow-md space-y-6">
            <!-- Schedule Section -->
            <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-bold text-blue-800 mb-4">My Schedule</h2>
                <div class="space-y-4">
                    <?php
                    if (!empty($scheduleResult)) {
                        foreach ($scheduleResult as $row) {
                            $dayFull = $daysMap[$row['workout_date']];
                            $time = htmlspecialchars($row['workout_time']);
                            $activity = htmlspecialchars($row['activity']);
                            echo "
                                <div class='flex justify-between items-center border-b pb-2'>
                                    <span class='font-medium'>{$dayFull} - {$activity}</span>
                                    <span class='bg-gray-200 text-blue-800 px-3 py-1 rounded-full'>{$time}</span>
                                </div>
                            ";
                        }
                    } else {
                        echo "<p class='text-gray-600'>No schedules found. <a href='my_schedule.php' class='text-blue-500 hover:underline'>Add a schedule</a>.</p>";
                    }
                    ?>
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

            <!-- Clubs Section -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-800">Your Clubs</h3>
                <div class="mt-4 flex space-x-4">
                    <img class="w-10 h-10 rounded-md" src="https://via.placeholder.com/40" alt="Club 1">
                    <img class="w-10 h-10 rounded-md" src="https://via.placeholder.com/40" alt="Club 2">
                    <img class="w-10 h-10 rounded-md" src="https://via.placeholder.com/40" alt="Club 3">
                </div>
                <a href="#" class="mt-4 block text-blue-500 text-sm hover:underline">View All Clubs</a>
            </div>
        </aside>
    </div>
</div>
</body>
</html>
