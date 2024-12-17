<?php
require_once '../backend/db.php'; // Database connection

function fetchMatches($conn) {
    $results = '';

    // Fetch input
    $workout = $_POST['exercise'] ?? '';
    $days = $_POST['days'] ?? [];
    $time = $_POST['time'] ?? '';

    // Input validation
    if ($workout == 'blank' || $time == 'blank' || empty($days)) {
        return "<p style='color: red; text-align: center;'>Please select a workout, at least one day, and a time.</p>";
    }

    // Ensure $days is an array
    if (!is_array($days)) {
        $days = [$days];
    }

    // Prepare placeholders for the days
    $dayPlaceholders = implode(',', array_fill(0, count($days), '?'));

    // Correct SQL query: Replace 'users' with 'user_admin'
    $sql = "SELECT w.workout_date, w.workout_time, w.activity, ua.username, ua.user_id
            FROM workout w
            INNER JOIN user_admin ua ON w.user_id = ua.user_id
            WHERE w.activity = ? 
            AND w.workout_date IN ($dayPlaceholders) 
            AND w.workout_time = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return "<p style='color: red;'>SQL Error: " . $conn->error . "</p>";
    }

    // Dynamic parameter binding
    $types = 's' . str_repeat('s', count($days)) . 's'; // 's' for string parameters
    $params = array_merge([$workout], $days, [$time]);
    $stmt->bind_param($types, ...$params);

    // Execute and fetch results
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $results .= "<table class='w-full border-collapse bg-white shadow-md rounded-lg'>";
            $results .= "<thead>
                            <tr class='bg-gray-100'>
                                <th class='border p-3 text-left'>Day</th>
                                <th class='border p-3 text-left'>Time</th>
                                <th class='border p-3 text-left'>Activity</th>
                                <th class='border p-3 text-left'>Username</th>
                                <th class='border p-3 text-center'>Actions</th>
                            </tr>
                         </thead>";
            $results .= "<tbody>";
            while ($row = $result->fetch_assoc()) {
                $results .= "<tr>
                                <td class='border p-3'>" . htmlspecialchars($row['workout_date']) . "</td>
                                <td class='border p-3'>" . htmlspecialchars($row['workout_time']) . "</td>
                                <td class='border p-3'>" . htmlspecialchars($row['activity']) . "</td>
                                <td class='border p-3'>" . htmlspecialchars($row['username']) . "</td>
                                <td class='border p-3 text-center'>
                                    <a href='messages.php?receiver_id=" . htmlspecialchars($row['user_id']) . "' 
                                       class='bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600'>
                                       Message
                                    </a>
                                </td>
                             </tr>";
            }
            $results .= "</tbody></table>";
        } else {
            $results = "<p class='text-blue-500 text-center'>No matches found for the selected criteria.</p>";
        }
    } else {
        $results = "<p class='text-red-500 text-center'>Error executing query: " . $stmt->error . "</p>";
    }

    $stmt->close();
    return $results;
}
?>
