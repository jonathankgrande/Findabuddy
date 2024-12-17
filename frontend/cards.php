<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pageHeader = "Find-A-Buddy";
    if (file_exists('../includes/navbar.php')) {
        include '../includes/navbar.php';
    } else {
        echo "<p class='text-red-500'>Error: Navbar file not found.</p>";
    }


// Include necessary class files
require_once "../classes/dbh.classes.php";         
require_once "../classes/profileinfo.classes.php"; 
require_once "../classes/profileinfo-contr.classes.php";
require_once "../classes/profileinfo-view.classes.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$currentUserId = $_SESSION['user_id'];

// Retrieve open chat user IDs by replicating the logic in fetch_messages.php
$db = new Dbh();
$conn = $db->connect();

$query = "
    SELECT m.sender_id, m.receiver_id,
           IF(m.sender_id = :userid, u2.username, u1.username) AS username
    FROM messages m
    JOIN user_admin u1 ON m.sender_id = u1.user_id
    JOIN user_admin u2 ON m.receiver_id = u2.user_id
    WHERE m.sender_id = :userid OR m.receiver_id = :userid
    ORDER BY m.timestamp ASC
";

$stmt = $conn->prepare($query);
$stmt->execute([':userid' => $currentUserId]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$threads = [];
foreach ($result as $row) {
    $other_user_id = ($row['sender_id'] == $currentUserId) ? $row['receiver_id'] : $row['sender_id'];
    if (!isset($threads[$other_user_id])) {
        $threads[$other_user_id] = [
            'username' => $row['username'],
            'messages' => []
        ];
    }
}

$chatUserIds = array_keys($threads);

$profileView = new ProfileInfoView();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Profile Cards</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
<div class="container mx-auto pt-10">

<div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-md mb-6">
            <h2 class="text-blue-700 font-bold text-lg">Profiles You've Matched With!</h2>
            <p class="text-gray-700">
                Use this feature to learn more about the people you've matched with.
            </p>
        </div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
<?php foreach ($chatUserIds as $userId): 
    $profileInfo = $profileView->fetchProfileInfo($userId);
    if (!$profileInfo) {
        continue; // Skip if no profile found
    }

    // Fetch data for display
    ob_start(); $profileView->displayProfileImage($userId); $profileImage = ob_get_clean();
    ob_start(); $profileView->displayTitle($userId); $title = ob_get_clean();
    ob_start(); $profileView->displayNickName($userId); $nickName = ob_get_clean();
    ob_start(); $profileView->displayAge($userId); $age = ob_get_clean();
    ob_start(); $profileView->displayAbout($userId); $about = ob_get_clean();
    ob_start(); $profileView->displayEmail($userId); $email = ob_get_clean();
    ob_start(); $profileView->displayGender($userId); $gender = ob_get_clean();
    ob_start(); $profileView->displayNumber($userId); $phoneNumber = ob_get_clean();
    ob_start(); $profileView->displayAddress($userId); $address = ob_get_clean();
?>

    <div class="py-8 px-8 max-w-sm mx-auto bg-white rounded-xl shadow-lg border border-blue-200 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:gap-x-6">
        <img class="block mx-auto h-24 w-24 rounded-full border-4 border-gray-200 sm:mx-0 sm:shrink-0 object-cover" src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" />

        <div class="text-center space-y-2 sm:text-left sm:grow">
            <div class="space-y-0.5">
                <p class="text-lg text-black font-semibold">
                    <?php echo htmlspecialchars($nickName); ?>
                    <?php if(!empty($age)): ?>
                        <span class="text-gray-600 text-sm">, <?php echo htmlspecialchars($age); ?></span>
                    <?php endif; ?>
                </p>
                <?php if(!empty($title)): ?>
                    <p class="text-blue-700 font-medium italic">
                        <?php echo htmlspecialchars($title); ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if(!empty($about)): ?>
                <p class="text-gray-700"><?php echo htmlspecialchars($about); ?></p>
            <?php endif; ?>

            <div class="text-sm text-gray-600 space-y-1 mt-2">
                <?php if(!empty($email)): ?>
                <p><span class="font-semibold">Email:</span> <?php echo htmlspecialchars($email); ?></p>
                <?php endif; ?>
                <?php if(!empty($phoneNumber)): ?>
                <p><span class="font-semibold">Phone:</span> <?php echo htmlspecialchars($phoneNumber); ?></p>
                <?php endif; ?>
                <?php if(!empty($gender)): ?>
                <p><span class="font-semibold">Gender:</span> <?php echo htmlspecialchars($gender); ?></p>
                <?php endif; ?>
                <?php if(!empty($address)): ?>
                <p><span class="font-semibold">Location:</span> <?php echo htmlspecialchars($address); ?></p>
                <?php endif; ?>
            </div>

            <!-- Message Button -->
            <a href="messages.php?receiver_id=<?php echo urlencode($userId); ?>"
               class="inline-block mt-3 px-4 py-1 text-sm text-blue-600 font-semibold rounded-full border border-blue-200 hover:text-white hover:bg-blue-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
               Message
            </a>
        </div>
    </div>

<?php endforeach; ?>
</div>

</body>
</html>
