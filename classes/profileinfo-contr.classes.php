<?php

// ProfileInfoContr class extends the ProfileInfo class to inherit its properties and methods.
class ProfileInfoContr extends ProfileInfo {
    
    // Private properties to store user information.
    private $userId;         // Main user ID.
    private $altId;          // Alternate ID. This may be changed to username or name for clarity because altId is a bit confusing at the moment.
    private $nickname;       // Nickname of the user
    private $gender;         // Gender of the user
    private $age;            // Age of the user
    private $phoneNumber;    // Phone number of the user
    private $address;        // Address or location of the user

    // Constructor to initialize user details.
    public function __construct($userId, $altId, $nickname, $gender, $age, $phoneNumber, $address) {
        // Assign values to the private properties
        $this->userId = $userId;
        $this->altId = $altId;
        $this->nickname = $nickname;
        $this->gender = $gender;
        $this->age = $age;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
    }

    // Function to set and display default profile information.
    public function defaultProfileInfo() {
        // Generate personalized profile information.
        $profileAbout = "Lets get to know each other! Tell us a little about yourself: What are your interests, hobbies, or something unique about you? We would love to hear your story!";
        $profileTitle = "Hi! I am " . $this->userId;
        $profileText = "Welcome, everyone! We're thrilled to have you here as part of this amazing community. Whether you're here to share ideas, learn new things, or just connect with others, we're excited to grow together!";
        
        $profileNickname = "Nickname: " . $this->nickname;
        $profileGender = "Gender: " . $this->gender;
        $profileAge = "Age: " . $this->age;
        $profileNumber = "Phone Number: " . $this->phoneNumber;
        $profileAddy = "Borough: " . $this->address; //For location we can use boroughs

        // Set or display profile information (assuming setProfileInfo is a method in the parent class).
        $this->setProfileInfo($profileAbout, $profileTitle, $profileText, $profileNickname, $profileGender, $profileAge, $profileNumber, $profileAddress, $this->userId);
    }

    public function updateProfileInfo($about, $introTitle, $introText, $nickName, $gender, $age, $email, $phoneNumber, $userAddress ){
        //error handlers
        if($this->emptyInputCheck($about, $introTitle, $introText, $nickName, $gender, $age, $email, $phoneNumber, $userAddress) == true){
            header("loction: ../profile.php?error=stmtfailed");
            exit();
        }
        //UPDATE PROFILE INFO
        $this->setNewProfileInfo($about, $introTitle, $introText, $nickName, $gender, $age, $email, $phoneNumber, $userAddy,  $userid);

    }

    private function emptyInputCheck($about, $introTitle, $introText, $nickName, $gender, $age, $email, $phoneNumber, $userAddress){
        $result;
        if(empty($about) || empty($introtitle) || empty($introtext) || empty($nickname) || empty($gender) || empty($age) || empty($email) || empty($phoneNumber) || empty($userAddress)){
            $result = true;
        }
        else {
            $result = false;
        }
        return $result;
        }
    }