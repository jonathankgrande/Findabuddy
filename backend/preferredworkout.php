<body class = "bg-gray-100 text-gray-800 font-sans">
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
         </tbody>
            </table>
            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 mt-4 rounded-lg hover:bg-blue-700 focus:outline-none block mx-auto">
                Submit
            </button>
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
    </div>
</body>
</html>
