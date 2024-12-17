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

    if (strlen($password) < 8) {
        throw new Exception("Password must be at least 8 characters long.");
    }
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function handleProfileImage($firstName, $lastName, $userName) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = './uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['profile_image']['name']));
        $filePath = $uploadDir . $fileName;

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array(mime_content_type($_FILES['profile_image']['tmp_name']), $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
        }

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $filePath)) {
            return $filePath;
        } else {
            throw new Exception("Failed to move uploaded file to target directory.");
        }
    } elseif (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive.",
            UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive.",
            UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload."
        ];
        $errorCode = $_FILES['profile_image']['error'];
        $errorMessage = $errorMessages[$errorCode] ?? "Unknown upload error.";
        throw new Exception("Error uploading profile image: " . $errorMessage);
    }

    // Generate default profile image if no file is uploaded
    return generateDefaultProfileImage($firstName, $lastName, $userName);
}

function generateDefaultProfileImage($firstName, $lastName, $userName) {
    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
    $bgColor = sprintf("#%06X", mt_rand(0, 0xFFFFFF));

    $image = imagecreate(200, 200);
    $backgroundColor = sscanf($bgColor, "#%02x%02x%02x");
    $bgColor = imagecolorallocate($image, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $fontSize = 5;
    $textWidth = imagefontwidth($fontSize) * strlen($initials);
    $textHeight = imagefontheight($fontSize);

    $x = (imagesx($image) - $textWidth) / 2;
    $y = (imagesy($image) - $textHeight) / 2;

    imagestring($image, $fontSize, $x, $y, $initials, $textColor);

    $profileImage = "uploads/" . $userName . "_default.png";
    imagepng($image, $profileImage);
    imagedestroy($image);

    return $profileImage;
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
        $hashedPassword = hashPassword($password);
        $profileImage = handleProfileImage($firstName, $lastName, $userName);

        saveUserToDatabase($pdo, $firstName, $lastName, $userName, $hashedPassword, $profileImage);

        session_start();
        $_SESSION['userId'] = $userName;
        header("Location: ../frontend/homepage.php");
        exit();
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
