<?php

class ProfileInfo extends Dbh {

    // Retrieve profile information for a user
    protected function getProfileInfo($userId) {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM athletes WHERE users_id = ?;');
            $stmt->execute([$userId]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Profile not found.");
            }

            return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row instead of all rows
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log error for debugging
            return null; // Return null in case of error
        }
    }

    // Update profile information for a user
    protected function setNewProfileInfo($altAbout, $altTitle, $altText, $nickName, $gender, $age, $email, $phoneNumber, $userAddy, $userId) {
        try {
            $stmt = $this->connect()->prepare(
                'UPDATE athletes SET 
                    alt_about = ?, 
                    alt_introtitle = ?, 
                    alt_introtext = ?, 
                    nick_name = ?, 
                    gender = ?, 
                    age = ?, 
                    email = ?, 
                    phone_number = ?, 
                    user_address = ? 
                WHERE users_id = ?;'
            );

            return $stmt->execute([$altAbout, $altTitle, $altText, $nickName, $gender, $age, $email, $phoneNumber, $userAddy, $userId]);
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log error for debugging
            return false; // Return false in case of error
        }
    }

    // Insert new profile information
    protected function setProfileInfo($altAbout, $altTitle, $altText, $nickName, $gender, $age, $email, $phoneNumber, $userAddy, $userId) {
        try {
            $stmt = $this->connect()->prepare(
                'INSERT INTO athletes 
                    (alt_about, alt_introtitle, alt_introtext, nick_name, gender, age, email, phone_number, user_address, users_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);'
            );

            return $stmt->execute([$altAbout, $altTitle, $altText, $nickName, $gender, $age, $email, $phoneNumber, $userAddy, $userId]);
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log error for debugging
            return false; // Return false in case of error
        }
    }
}
