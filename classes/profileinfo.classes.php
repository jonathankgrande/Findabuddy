<?php

class ProfileInfo extends Dbh {
    protected function getProfileInfo($userid){
        $stmt = $this->connect()->prepare('SELECT * FROM athletes WHERE users_id = ?;');

        if($stmt->execute(array($userId))){
            $stmt = null;
            header("loction: profile.php?error=stmtfailed");
            exit();
            /*avoids nested statements*/ 
            }
        if($stmt>rowCount() == 0){
            $stmt = null;
            header("loction: profile.php?error=profilenotfound");
            exit();
            }
             
            $profileData = $stmt->fetchAll(PDO::FETCH_ASSOC);  /* when i grab the data from the database i actually have to use it inside my code and reference to the data i want to be able to refernece to the data based on the column names inside the database so if i want to get userid then i just write user_id and i will get that particular piece of data*/

            return $profileData;
        }
    }

class ProfileInfo extends Dbh {
    protected function setNewProfileInfo($altAbout, $altTitle, $altText, $nickName, $gender, $age, $phoneNumber, $userAddy,  $userid){
        $stmt = $this->connect()->prepare('UPDATE athletes SET alt_about = ?, alt_introtitle = ?, alt_introtext = ?, nick_name = ?, gender = ?, age = ?, phone_number = ?, user_address = ?  WHERE users_id =?;');

        if($stmt->execute(array($altAbout, $altTitle, $altText, $nickName, $gender, $age, $phoneNumber, $userAddy,  $userid))){
            $stmt = null;
            header("loction: profile.php?error=stmtfailed");
            exit();
            /*avoids nested statements*/ 
            }

            $stmt = null;
       
        }

        protected function setProfileInfo($altAbout, $altTitle, $altText, $nickName, $gender, $age, $phoneNumber, $userAddy,  $userid){ 
            $stmt = $this->connect()->prepare('INSERT INTO athletes (alt_about, alt_introtitle, alt_introtext, nick_name, gender, age, phone_number, user_address) VALUES (?,?,?,?,?,?,?,?;');
    
            if($stmt->execute(array($altAbout, $altTitle, $altText, $nickName, $gender, $age, $phoneNumber, $userAddy,  $userid))){
                $stmt = null;
                header("loction: profile.php?error=stmtfailed");
                exit();
                /*avoids nested statements*/ 
                }
    
                $stmt = null;
           
            }


}

