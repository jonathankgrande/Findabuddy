<?php
require_once "profileinfo.classes.php"; // Load ProfileInfo before using it

class ProfileInfoContr extends ProfileInfo {
    private $userId;

    // Constructor to initialize user ID
    public function __construct($userId) {
        $this->userId = $userId;
    }

    // Update or insert profile information
    public function updateProfileInfo($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress, $profileImage = null) {
        // Check for empty fields
        if ($this->emptyInputCheck($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress)) {
            header("Location: ../profile_settings.php?error=emptyfields");
            exit();
        }

        // Update profile info in the athletes table
        $this->upsertProfileInfo($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress, $this->userId);

        // Update profile image if provided
        if ($profileImage) {
            $this->updateProfileImage($profileImage, $this->userId);
        }
    }

    // Fetch all profile information (from athletes table)
    public function fetchProfileInfo() {
        return $this->getProfileInfo($this->userId);
    }

    // Check for empty input
    private function emptyInputCheck($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress) {
        return empty($about) || empty($title) || empty($nickName) || empty($gender) || empty($age) || empty($email) || empty($phoneNumber) || empty($userAddress);
    }

    // Update profile image in user_admin table
    protected function updateProfileImage($profileImage, $userId) {
        try {
            $conn = $this->connect();
            $query = "UPDATE user_admin SET profile_image = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$profileImage, $userId]);
        } catch (Exception $e) {
            error_log("Error updating profile image: " . $e->getMessage());
            throw new Exception("Failed to update profile image.");
        }
    }
}
