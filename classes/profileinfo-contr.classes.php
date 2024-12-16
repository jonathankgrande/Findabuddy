<?php
require_once "profileinfo.classes.php"; // Add this to load ProfileInfo before using it

class ProfileInfoContr extends ProfileInfo {
    private $userId;

    // Constructor to initialize user ID
    public function __construct($userId) {
        $this->userId = $userId;
    }

    // Update or insert profile information
    public function updateProfileInfo($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress) {
        // Error handlers
        if ($this->emptyInputCheck($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress)) {
            header("Location: ../profile.php?error=emptyfields");
            exit();
        }

        // Call the new upsert method
        $this->upsertProfileInfo($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress, $this->userId);
    }

    // Check for empty input
    private function emptyInputCheck($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress) {
        return empty($about) || empty($title) || empty($nickName) || empty($gender) || empty($age) || empty($email) || empty($phoneNumber) || empty($userAddress);
    }
}
