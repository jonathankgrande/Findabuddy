<?php

class UserUtility {
    public static function validatePasswords($password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            throw new Exception("Passwords do not match.");
        }
    }

    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function handleProfileImage($firstName, $lastName, $userName) {
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $profileImage = $uploadDir . basename($_FILES['profile_image']['name']);
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImage);
            return $profileImage;
        } else {
            return self::generateDefaultProfileImage($firstName, $lastName, $userName);
        }
    }

    public static function generateDefaultProfileImage($firstName, $lastName, $userName) {
        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
        $bgColor = sprintf("#%06X", mt_rand(0, 0xFFFFFF));

        $image = imagecreate(200, 200);
        $backgroundColor = sscanf($bgColor, "#%02x%02x%02x");
        $bgColor = imagecolorallocate($image, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($initials);
        $textHeight = imagefontheight($fontSize);

        $x = (imagesx($image) - $textWidth) / 2;
        $y = (imagesy($image) - $textHeight) / 2;

        imagestring($image, $fontSize, $x, $y, $initials, $textColor);

        $profileImage = "uploads/" . $userName . "_default.png";
        imagepng($image, $profileImage);
        imagedestroy($image);

        return $profileImage;
    }
}
