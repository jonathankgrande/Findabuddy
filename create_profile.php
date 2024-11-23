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
        die("Database connection failed: " . $e->getMessage());
    }
}

function validatePasswords($password, $confirmPassword) {
    if ($password !== $confirmPassword) {
        throw new Exception("Passwords do not match.");
    }
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function handleProfileImage($firstName, $lastName, $userName) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $profileImage = $uploadDir . basename($_FILES['profile_image']['name']);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImage);
        return $profileImage;
    } else {
        return generateDefaultProfileImage($firstName, $lastName, $userName);
    }
}

function generateDefaultProfileImage($firstName, $lastName, $userName) {
    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
    $bgColor = sprintf("#%06X", mt_rand(0, 0xFFFFFF));

    // Create image
    $image = imagecreate(200, 200);
    $backgroundColor = sscanf($bgColor, "#%02x%02x%02x");
    $bgColor = imagecolorallocate($image, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $fontSize = 5;
    $textWidth = imagefontwidth($fontSize) * strlen($initials);
    $textHeight = imagefontheight($fontSize);

    // Center the text
    $x = (imagesx($image) - $textWidth) / 2;
    $y = (imagesy($image) - $textHeight) / 2;

    imagestring($image, $fontSize, $x, $y, $initials, $textColor);

    // Save image
    $profileImage = "uploads/" . $userName . "_default.png";
    imagepng($image, $profileImage);
    imagedestroy($image);

    return $profileImage;
}

function saveUserToDatabase($pdo, $firstName, $lastName, $userName, $passwordHash) {
    try {
        $stmt = $pdo->prepare("INSERT INTO user_admin (username, passcode, first_name, last_name) 
                               VALUES (:username, :passcode, :first_name, :last_name)");
        $stmt->execute([
            ':username' => $userName,
            ':passcode' => $passwordHash,
            ':first_name' => $firstName,
            ':last_name' => $lastName
        ]);
    } catch (PDOException $e) {
        throw new Exception("Error saving data: " . $e->getMessage());
    }
}

// Main Execution
try {
    $pdo = connectToDatabase($host, $dbname, $username, $password);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstName = htmlspecialchars($_POST['first_name']);
        $lastName = htmlspecialchars($_POST['last_name']);
        $userName = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        validatePasswords($password, $confirmPassword);
        $passwordHash = hashPassword($password);
        $profileImage = handleProfileImage($firstName, $lastName, $userName);

        saveUserToDatabase($pdo, $firstName, $lastName, $userName, $passwordHash);

        echo "<h2>Profile Created</h2>";
        echo "<p>Name: $firstName $lastName</p>";
        echo "<p>Username: $userName</p>";
        echo "<img src='$profileImage' alt='Profile Image' width='200'>";
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?>
