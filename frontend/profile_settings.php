<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require_once "../classes/dbh.classes.php";
require_once "../classes/profileinfo-contr.classes.php";
require_once "../classes/profileinfo.classes.php";


// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$profileContr = new ProfileInfoContr($userId);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $about = $_POST['about'] ?? '';
        $title = $_POST['title'] ?? '';
        $nickname = $_POST['nickname'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $age = $_POST['age'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $borough = $_POST['borough'] ?? '';

        // Handle profile image upload
        $profileImage = handleProfileImage();

        // Update profile info
        $profileContr->updateProfileInfo($about, $title, $nickname, $gender, $age, $email, $phone, $borough, $profileImage);
        header("Location: profile.php?update=success");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch profile information
$profileInfo = $profileContr->fetchProfileInfo();

// Fetch profile image separately from the user_admin table
$profileImage = '../uploads/default.png'; // Default image
try {
    $conn = (new Dbh())->connect();
    $stmt = $conn->prepare("SELECT profile_image FROM user_admin WHERE user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetchColumn();
    if ($result && file_exists($result)) {
        $profileImage = $result;
    }
} catch (Exception $e) {
    error_log("Error fetching profile image: " . $e->getMessage());
}

function handleProfileImage() {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['profile_image']['name']);
        $filePath = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array(mime_content_type($_FILES['profile_image']['tmp_name']), $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
        }

        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $filePath)) {
            throw new Exception("Failed to upload the profile image.");
        }

        return "../uploads/" . $fileName; // Save relative path in database
    }
    return null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

<?php
$pageHeader = "Settings";
if (file_exists('../includes/navbar.php')) {
    include '../includes/navbar.php';
} else {
    echo "<p class='text-red-500'>Error: Navbar file not found.</p>";
}
?>

<div class="container mx-auto pt-10">
    <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
        <h2 class="text-blue-700 font-bold text-lg">Settings</h2>
        <p class="text-gray-700">Manage your profile and customize your preferences.</p>
    </div>

    <main class="w-3/4 bg-white p-6 rounded-lg shadow-md mx-auto">
        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-blue-800 mb-4">Edit Your Profile</h3>
            <?php if (isset($error)) echo "<p class='text-red-500'>$error</p>"; ?>
            <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
                <!-- Profile Image -->
                <div class="flex items-center space-x-4">
                    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="w-32 h-32 rounded-full border">
                    <input type="file" name="profile_image" accept="image/*" class="border p-2 rounded">
                </div>
                <!-- About -->
                <div>
                    <label for="about" class="block text-sm font-medium text-gray-800">About:</label>
                    <textarea id="about" name="about" class="w-full p-3 border border-gray-300 rounded-lg"><?php echo htmlspecialchars($profileInfo['about'] ?? ''); ?></textarea>
                </div>
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-800">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($profileInfo['title'] ?? ''); ?>" class="w-full p-3 border border-gray-300 rounded-lg">
                </div>
                <!-- Nickname -->
                <div>
                    <label for="nickname" class="block text-sm font-medium text-gray-800">Nickname:</label>
                    <input type="text" id="nickname" name="nickname" value="<?php echo htmlspecialchars($profileInfo['nick_name'] ?? ''); ?>" class="w-full p-3 border border-gray-300 rounded-lg">
                </div>
                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-800">Gender:</label>
                    <select id="gender" name="gender" class="w-full p-3 border border-gray-300 rounded-lg">
                        <option value="">Select Gender</option>
                        <option value="male" <?php echo isset($profileInfo['gender']) && $profileInfo['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo isset($profileInfo['gender']) && $profileInfo['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                        <option value="non_binary" <?php echo isset($profileInfo['gender']) && $profileInfo['gender'] == 'non_binary' ? 'selected' : ''; ?>>Non-binary</option>
                    </select>
                </div>
                <!-- Other Fields -->
                <input type="number" id="age" name="age" placeholder="Age" value="<?php echo htmlspecialchars($profileInfo['age'] ?? ''); ?>" class="w-full p-3 border rounded-lg">
                <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($profileInfo['email'] ?? ''); ?>" class="w-full p-3 border rounded-lg">
                <input type="tel" id="phone" name="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($profileInfo['phone_number'] ?? ''); ?>" class="w-full p-3 border rounded-lg">
                <input type="text" id="borough" name="borough" placeholder="Borough" value="<?php echo htmlspecialchars($profileInfo['user_address'] ?? ''); ?>" class="w-full p-3 border rounded-lg">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg">Save Changes</button>
            </form>
        </div>
    </main>
</div>
</body>
</html>
