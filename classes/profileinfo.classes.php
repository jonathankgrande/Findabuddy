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

    // Upsert profile information (Insert or Update if exists)
    protected function upsertProfileInfo($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress, $userId) {
        try {
            $stmt = $this->connect()->prepare(
                'INSERT INTO athletes (user_id, about, title, nick_name, gender, age, email, phone_number, user_address) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE 
                 about = VALUES(about),
                 title = VALUES(title),
                 nick_name = VALUES(nick_name),
                 gender = VALUES(gender),
                 age = VALUES(age),
                 email = VALUES(email),
                 phone_number = VALUES(phone_number),
                 user_address = VALUES(user_address);'
            );

            if (!$stmt->execute([$userId, $about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress])) {
                throw new Exception("Failed to upsert profile for user ID: $userId");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in upsertProfileInfo: " . $e->getMessage());
            return false;
        }
    }
}

