<?php

class ProfileInfoView extends ProfileInfo {

    // Fetch and display the 'About' section
    public function displayAbout($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        
            echo $profileInfo[0]["about"];
        }

    // Fetch and display the title
    public function displayTitle($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        if (!empty($profileInfo)) {
            echo htmlspecialchars($profileInfo["title"]);
        } else {
            echo "No title available.";
        }
    }

    // Fetch and display the nickname
    public function displayNickName($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        if (!empty($profileInfo)) {
            echo htmlspecialchars($profileInfo["nick_name"]);
        } else {
            echo "No nickname available.";
        }
    }

    // Fetch and display the gender
    public function displayGender($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        if (!empty($profileInfo)) {
            echo htmlspecialchars($profileInfo["gender"]);
        } else {
            echo "No gender information available.";
        }
    }

    // Fetch and display the age
    public function displayAge($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        if (!empty($profileInfo)) {
            echo htmlspecialchars($profileInfo["age"]);
        } else {
            echo "No age information available.";
        }
    }

    // Fetch and display the email
    public function displayEmail($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        if (!empty($profileInfo)) {
            echo htmlspecialchars($profileInfo["email"]);
        } else {
            echo "No email information available.";
        }
    }

    // Fetch and display the phone number
    public function displayNumber($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        if (!empty($profileInfo)) {
            echo htmlspecialchars($profileInfo["phone_number"]);
        } else {
            echo "No phone number available.";
        }
    }

    // Fetch and display the address
    public function displayAddress($userId) {
        $profileInfo = $this->getProfileInfo($userId);
        if (!empty($profileInfo)) {
            echo htmlspecialchars($profileInfo["user_address"]);
        } else {
            echo "No address available.";
        }
    }

    public function fetchProfileInfo($userId) {
        return $this->getProfileInfo($userId);
    }
}
