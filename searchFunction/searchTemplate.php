<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

//Connecting database
$servername = "localhost";
$username = "csc350";
$password = "xampp";
$dbname = "findabuddy";

$conn = new mysqli($servername, $username, $password, $dbname);

// Checks connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch matching results
function fetchMatches($conn) {
    $results = '';

    // Fetches form
    $workout = $_POST['exercise'] ?? '';
    $days = $_POST['days'] ?? [];
    $time = $_POST['time'] ?? '';

    if ($workout == 'blank' || $time == 'blank' || empty($days)) {
        return "<p style='color: red; text-align: center;'>Please select a workout, at least one day, and a time.</p>";
    }

    // Sets days to an array if multiple
    if (!is_array($days)) {
        $days = [$days];
    }

    // Prepare placeholders
    $dayPlaceholders = implode(',', array_fill(0, count($days), '?'));

    // Builds SQL query
    $sql = "
        SELECT * FROM workout 
        WHERE exercise_type = ? 
        AND workout_date IN ($dayPlaceholders)
        AND workout_time = ?
    ";

    // Prepares statement
    $stmt = $conn->prepare($sql);

    // Bind params
    $types = str_repeat('s', count($days) + 2);
    $params = array_merge([$workout], $days, [$time]);
    $stmt->bind_param($types, ...$params);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Output results
        if ($result && $result->num_rows > 0) {
            $results .= "<table border='1' align='center'>";
            $results .= "<tr><th>Workout</th><th>Day</th><th>Time</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $results .= "<tr><td>" . htmlspecialchars($row['exercise_type']) . "</td>
                            <td>" . htmlspecialchars($row['workout_date']) . "</td>
                            <td>" . htmlspecialchars($row['workout_time']) . "</td></tr>";
            }
            $results .= "</table>";
        } else {
            $results = "<p style='color: blue; text-align: center;'>No matches found for the selected criteria.</p>";
        }
    } else {
        $results = "<p style='color: red; text-align: center;'>Query failed: " . $stmt->error . "</p>";
    }

    $stmt->close();
    return $results;
}

//results
$results = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $results = fetchMatches($conn);
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Search Results</title>
</head>

<body>
  <form action="searchTemplate.php" method="post">
    <table border="1" align="center">
      <tr>
        <td align="center">Workout</td>
        <td align="center">Days Available</td>
        <td align="center">Time</td>
      </tr>
      <tr>
        <td>
          <select name="exercise">
            <option value="blank" default>Select Workout</option>
            <option value="bike">Biking</option>
            <option value="run">Running</option>
            <option value="walk">Walking</option>
          </select>
        </td>
        <td>
          <label>
            <input type="checkbox" name="days[]" value="Mo"> Monday &nbsp;
            <input type="checkbox" name="days[]" value="Tu"> Tuesday &nbsp;
            <input type="checkbox" name="days[]" value="We"> Wednesday &nbsp;
            <input type="checkbox" name="days[]" value="Th"> Thursday &nbsp;
            <input type="checkbox" name="days[]" value="Fr"> Friday &nbsp;
            <input type="checkbox" name="days[]" value="Sa"> Saturday &nbsp;
            <input type="checkbox" name="days[]" value="Su"> Sunday &nbsp;
          </label>
        </td>
        <td>
          <select name="time">
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
        </td>
      </tr>
      <tr>
        <td colspan="3" align="center">
          <button type="submit">Search For Match</button>
        </td>
      </tr>
    </table>
  </form>

  <br><br>
  <!-- Display the results -->
  <div align="center">
    <?php echo $results; ?>
  </div>
</body>

</html>
