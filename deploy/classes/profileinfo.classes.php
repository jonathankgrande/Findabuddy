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
            error_log("Error in getProfileInfo: " . $e->getMessage());
            return null;
        }
    }

    // Upsert profile information (Insert or Update if exists)
    protected function upsertProfileInfo($about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress, $userId, $profileImage = null) {
        try {
            $conn = $this->connect();
    
            // Step 1: Update the athletes table
            $queryAthletes = '
                INSERT INTO athletes (user_id, about, title, nick_name, gender, age, email, phone_number, user_address) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    about = VALUES(about),
                    title = VALUES(title),
                    nick_name = VALUES(nick_name),
                    gender = VALUES(gender),
                    age = VALUES(age),
                    email = VALUES(email),
                    phone_number = VALUES(phone_number),
                    user_address = VALUES(user_address)';
    
            $stmt = $conn->prepare($queryAthletes);
            $stmt->execute([$userId, $about, $title, $nickName, $gender, $age, $email, $phoneNumber, $userAddress]);
    
            // Step 2: Update profile_image in user_admin table (only if a new image is uploaded)
            if ($profileImage) {
                $queryUserAdmin = '
                    UPDATE user_admin 
                    SET profile_image = ? 
                    WHERE user_id = ?';
                    
                $stmt = $conn->prepare($queryUserAdmin);
                $stmt->execute([$profileImage, $userId]);
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error in upsertProfileInfo: " . $e->getMessage());
            return false;
        }
    }
}
