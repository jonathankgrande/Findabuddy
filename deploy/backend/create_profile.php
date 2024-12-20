<?php
// Database connection settings
$host = "localhost";
$dbname = "findabuddy";
$username = "csc350";
$password = "xampp";

function connectToDatabase($host, $dbname, $username, $password) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Could not connect to the database. Please try again later.");
    }
}

function validatePasswords($password, $confirmPassword) {
    if ($password !== $confirmPassword) {
        throw new Exception("Passwords do not match.");
    }
    if (strlen($password) < 8) {
        throw new Exception("Password must be at least 8 characters long.");
    }
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function saveUserToDatabase($pdo, $firstName, $lastName, $userName, $hashedPassword) {
    try {
        $stmt = $pdo->prepare("INSERT INTO user_admin (username, passcode, first_name, last_name) 
                               VALUES (:username, :passcode, :first_name, :last_name)");
        $stmt->execute([
            ':username' => $userName,
            ':passcode' => $hashedPassword,
            ':first_name' => $firstName,
            ':last_name' => $lastName
        ]);
    } catch (PDOException $e) {
        error_log("Error saving user to database: " . $e->getMessage());
        throw new Exception("An error occurred while saving your data. Please try again.");
    }
}

// Main Execution
try {
    $pdo = connectToDatabase($host, $dbname, $username, $password);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstName = htmlspecialchars($_POST['first_name']);
        $lastName = htmlspecialchars($_POST['last_name']);
        $userName = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        validatePasswords($password, $confirmPassword);
        $hashedPassword = hashPassword($password);

        saveUserToDatabase($pdo, $firstName, $lastName, $userName, $hashedPassword);

        session_start();
        $_SESSION['userId'] = $userName;
        header("Location: ../frontend/homepage.php");
        exit();
    }
} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
