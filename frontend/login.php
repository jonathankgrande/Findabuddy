<?php
session_start();
include '../backend/db.php'; // Include your database connection

// Check if the user is already logged in, if so, redirect them
if (isset($_SESSION['username'])) {
    header("Location: homepage.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form inputs
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL injection by sanitizing the inputs
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the user exists in the database
    $query = "SELECT * FROM user_admin WHERE username = '$username' AND passcode = '$password'";
    $result = mysqli_query($conn, $query);

    // Check if user exists and the password matches
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Store user info in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // Redirect to messages page (or homepage)
        header("Location: homepage.php");
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">
    <div class="container mx-auto my-10 p-6 max-w-sm bg-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-blue-800 mb-6 text-center">Login</h1>

        <!-- Display error message if login fails -->
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 border rounded-md mt-2" required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded-md mt-2" required>
            </div>

            <div class="text-center">
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Login</button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">Don't have an account? <a href="" class="text-blue-500">Register here</a></p>
        </div>
    </div>
</body>
</html>
