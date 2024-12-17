<?php
require_once 'dbh.classes.php';

class BuddyMatcher extends Dbh {
    
    // Fetch the user's borough (user_address) by their user ID
    public function getUserBorough($userId) {
        $sql = "SELECT user_address FROM athletes WHERE user_id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$userId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['user_address'] : null;
    }

    // Match buddies based on the same borough
    public function getMatchingBuddiesByBorough($userId) {
        // Get the user's borough dynamically
        $userBorough = $this->getUserBorough($userId);
        if (!$userBorough) {
            return [];
        }

        // Query for matching users
        $sql = "SELECT user_id, nick_name, user_address, age, email 
                FROM athletes 
                WHERE user_address = ? AND user_id != ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$userBorough, $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Example usage for testing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start(); // Ensure session is active
    require_once 'BuddyMatcher.php';

    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        $matcher = new BuddyMatcher();
        $matches = $matcher->getMatchingBuddiesByBorough($userId);

        echo json_encode($matches);
    } else {
        echo json_encode(['error' => 'User not logged in.']);
    }
}
?>
