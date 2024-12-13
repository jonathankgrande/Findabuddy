<?php

class ProfileInfo extends Dbh {

    // Retrieve profile information for a user
    protected function getProfileInfo($userId) {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM athletes WHERE user_id = ?;');
            $stmt->execute([$userId]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Profile not found for user ID: $userId");
            }

            return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row
        } catch (Exception $e) {
            error_log("Error in getProfileInfo: " . $e->getMessage()); // Log error for debugging
            return null;
        }
    }

    // Update profile information for a user
    protected function setNewProfileInfo($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress, $userId) {
        try {
            $stmt = $this->connect()->prepare(
                'UPDATE athletes 
                 SET about = ?, title = ?, nick_name = ?, gender = ?, age = ?, email = ?, phone_number = ?, user_address = ? 
                 WHERE user_id = ?;'
            );

            if (!$stmt->execute([$about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress, $userId])) {
                throw new Exception("Failed to update profile for user ID: $userId");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in setNewProfileInfo: " . $e->getMessage());
            return false;
        }
    }

    // Insert new profile information (optional, if needed)
    protected function setProfileInfo($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress, $userId) {
        try {
            $stmt = $this->connect()->prepare(
                'INSERT INTO athletes (about, title, nick_name, gender, age, email, phone_number, user_address, user_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);'
            );

            if (!$stmt->execute([$about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress, $userId])) {
                throw new Exception("Failed to insert profile for user ID: $userId");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in setProfileInfo: " . $e->getMessage());
            return false;
        }
    }
}
