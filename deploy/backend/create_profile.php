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

function handleProfileImage($firstName, $lastName, $userName) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['profile_image']['name']));
        $filePath = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['profile_image']['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
        }
        if ($_FILES['profile_image']['size'] > 5 * 1024 * 1024) {
            throw new Exception("File size exceeds the maximum limit of 5MB.");
        }
        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $filePath)) {
            throw new Exception("Failed to move uploaded file to target directory.");
        }

        return $filePath;
    } elseif (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        throw new Exception("Error uploading profile image.");
    }

    return generateDefaultProfileImage($firstName, $lastName, $userName);
}

function generateDefaultProfileImage($firstName, $lastName, $userName) {
    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
    $bgColor = sprintf("#%06X", mt_rand(0, 0xFFFFFF));

    $image = imagecreate(200, 200);
    $rgb = sscanf($bgColor, "#%02x%02x%02x");
    $bgColor = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $fontSize = 5;
    $x = (200 - imagefontwidth($fontSize) * strlen($initials)) / 2;
    $y = (200 - imagefontheight($fontSize)) / 2;

    imagestring($image, $fontSize, $x, $y, $initials, $textColor);

    $filePath = "../uploads/{$userName}_default.png";
    imagepng($image, $filePath);
    imagedestroy($image);

    return $filePath;
}

function saveUserToDatabase($pdo, $firstName, $lastName, $userName, $hashedPassword, $profileImage) {
    try {
        $stmt = $pdo->prepare("INSERT INTO user_admin (username, passcode, first_name, last_name, profile_image) 
                               VALUES (:username, :passcode, :first_name, :last_name, :profile_image)");
        $stmt->execute([
            ':username' => $userName,
            ':passcode' => $hashedPassword,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':profile_image' => $profileImage
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
        $profileImage = handleProfileImage($firstName, $lastName, $userName);

        saveUserToDatabase($pdo, $firstName, $lastName, $userName, $hashedPassword, $profileImage);

        session_start();
        $_SESSION['userId'] = $userName;
        header("Location: ../frontend/homepage.php");
        exit();
    }
} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
