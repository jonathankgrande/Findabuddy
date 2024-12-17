<?php

class ProfileInfoView extends ProfileInfo {

    // Fetch and display the 'About' section
    public function displayAbout($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        echo htmlspecialchars($profileInfo["about"] ?? "No about information available.");
    }

    // Fetch and display the title
    public function displayTitle($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        echo htmlspecialchars($profileInfo["title"] ?? "No title available.");
    }

    // Fetch and display the nickname
    public function displayNickName($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        echo htmlspecialchars($profileInfo["nick_name"] ?? "No nickname available.");
    }

    // Fetch and display the gender
    public function displayGender($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        echo htmlspecialchars($profileInfo["gender"] ?? "No gender information available.");
    }

    // Fetch and display the age
    public function displayAge($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        echo htmlspecialchars($profileInfo["age"] ?? "No age information available.");
    }

    // Fetch and display the email
    public function displayEmail($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        echo htmlspecialchars($profileInfo["email"] ?? "No email information available.");
    }

    // Fetch and display the phone number
    public function displayNumber($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        echo htmlspecialchars($profileInfo["phone_number"] ?? "No phone number available.");
    }

    // Fetch and display the address
    public function displayAddress($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        echo htmlspecialchars($profileInfo["user_address"] ?? "No address available.");
    }

    // New Method: Fetch and display the profile image
    public function displayProfileImage($userId) {
        try {
            // Connect to the database and fetch the profile image path from the user_admin table
            $conn = $this->connect();
            $query = "SELECT profile_image FROM user_admin WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$userId]);
    
            $profileImage = $stmt->fetchColumn();
    
            // Check if the profile image path exists
            if ($profileImage && file_exists($profileImage)) {
                // Return the valid profile image path
                echo htmlspecialchars($profileImage);
            } else {
                // Fallback to default image if no profile image is found
                echo "../uploads/default.png";
            }
        } catch (Exception $e) {
            // Log the error and display the default image in case of an issue
            error_log("Error fetching profile image: " . $e->getMessage());
            echo "../uploads/default.png";
        }
    }
    

    // The issue before was that we were not fetching the profile image from the 'user_admin' table.
    // Instead, the code was trying to access it from the 'athletes' table, which does not contain the profile image column.
    // This new method directly queries the 'user_admin' table to fetch the profile image.

    // Fetch full profile info
    public function fetchProfileInfo($userId) {
        return $this->getProfileInfo($userId);
    }
}
