<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout Planner</title>
    <style>
        table {     /* Centers the table and styles it with a fixed width and collapsible borders. */
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {    /* table header(th), table data cell(td):  Adds borders and padding to table cells and centers their content. */ 
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        select:disabled {   /* When select is disabled, the background and text are gray */
            background-color: #f2f2f2;
            color: #ccc;
        }
        .submit-button {    /* Centers submit button, adds padding and font size */
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
    <script>
        function toggleDay(dayId) {     /* Handles enabling and disabling the dropdown menu based on user availablity (yes or no)*/
            const toggle = document.getElementById(`toggle-${dayId}`); /* gets the toggle and dropdown elements by dynamically generated Id's*/
            const dropdown = document.getElementById(`time-${dayId}`);  
            if (toggle.value === 'yes') {   /* enables preffered time dropdown menu is user selects yes */
                dropdown.disabled = false;
            }
            else {  /* disables dropdown menu is user selects no*/
                dropdown.disabled = true;
                dropdown.value = '';
            }
        }
    </script>
</head>
<body>
    <form method="POST" action=""> <!-- will likely have update action so that it leads to a different page --> 
        <table>
            <tr> <!-- table row(tr)-->
                <th>Day</th>
                <th>Workout?</th>
                <th>Preferred Time</th>
            </tr>
            <?php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; /* array of strings that holds all 7 days*/
                
                function formattedTime ($hour) { /* This function formats every hour of the dat */
                    $period = '';
                    $formattedHour = 0;

                    if ($hour < 12) {           
                        $period = 'AM';     //adds AM to hours less than 12  
                    }
                    else {                      
                        $period = 'PM';     //adds PM to hours greater than 12
                    }

                    if ($hour % 12 === 0) {
                        $formattedHour = 12; //1AM to 12PM
                    }
                    else {
                        $formattedHour = $hour % 12; //13-24 becomes 1PM to 12AM
                    }
                    return sprintf("%d:00 %s", $formattedHour, $period); //built in function that provides a template format, and it accepts 2 arguements
                }                 //%d turns into any number betwwen 1 and 12 %s turns into AM or PM to output ex: 7:00 AM

                foreach ($days as $day) { //Loops through all 7 days. Creates a row and toggle for each day
                    echo "
                        <tr> 
                            <td>{$day}</td>
                            <td> 
                                <select id='toggle-{$day}' name='toggle[{$day}]' onchange='toggleDay(\"{$day}\")'>
                                    <option value='no' selected>No</option>
                                    <option value='yes'>Yes</option>
                                </select>
                            </td>
                            <td>
                            <select id='time-{$day}' name='time[{$day}]' disabled>
                                <option value=''>Select Time</option>
                    ";

                    for ($hour = 0; $hour < 24; $hour++) { //this for loops generates all 24 hours of the day
                        $time = formattedTime($hour); //fromats in AM and PM
                        echo "<option value='{$time}'>{$time}</option>"; // This lets the user choose a desired time
                    }

                    echo "
                            </select>
                            </td>
                        </tr>
                    ";
                }
            ?>
        </table>
        <button type="submit" class="submit-button">Submit</button> <!-- button that submits data  -->
    </form>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { //verfies if the form was submitted using POST method
                $preferences = []; //stores day and time chosen by user
                foreach ($days as $day) {
                    if ($_POST['toggle'][$day] === 'yes' && !empty($_POST['time'][$day])) { //if yes, day and time is stored in $preference
                        $preferences[$day] = $_POST['time'][$day];
                    }
                }

                if (!empty($preferences)) {
                    echo "<h2>Your Preferences:</h2><ul>";  //prints preference: day and time if toggled yes.
                    foreach ($preferences as $day => $time) {
                        echo "<li><strong>{$day}:</strong> {$time}</li>"; 
                    }
                    echo "</ul>";
                }

                else {
                    echo "<h2>No preferences selected.</h2>"; //if toggled to no, and user submits, this message prints
                }
            }
        ?>
</body>
</html>