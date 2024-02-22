<?php
session_start();

require "../../core/database.php";
require "../../models/user.model.php";
require "../../core/utils.php";

function checkAvatarFile($avatar, $file, $type) {
    if (file_exists($file)) {
        return ['code' => 409, 'message' => "This file already exists."];
    }
    elseif ($avatar["size"] > 500 * 1024) {
        return ['code' => 400, 'message' => "Your avatar is too large (+500 ko)."];
    }
    elseif ($type != "png" && $type != "jpg" && $type != "jpeg" && $type != "gif" ) {
        return ['code' => 400, 'message' => "Only PNG, JPG, JPEG & GIF files are allowed."];
    }
    return ['code' => 200];
}

function uploadAvatar($userId, $avatar) {
    $dir = "../../assets/uploads/avatars/";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $fileType = strtolower(pathinfo($avatar["name"], PATHINFO_EXTENSION));
    $fileName = uniqid($userId . '_', true) . '.' . $fileType;
    $filePath = $dir . $fileName;

    $fileCheck = checkAvatarFile($avatar, $filePath, $fileType);
    if ($fileCheck['code'] !== 200) {
        return ['code' => $fileCheck['code'], 'message' => $fileCheck['message']];
    }

    if (!move_uploaded_file($avatar["tmp_name"], $filePath)) {
        return ['code' => 400, 'message' => "Sorry, there was an error uploading your file."];
    }

    return ['code' => 200, 'message' => null, 'file' => $fileName];
}

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $userId = $_SESSION['id'];
        $db = connectToDatabase();
        $user = getUserById($db, $userId);

        if (isset($_POST['fullname'])) {
            $fullname = trim(htmlspecialchars($_POST['fullname']));
            $fullname = preg_replace('/\s+/', ' ', $fullname);
            $fullname = html_entity_decode($fullname);
        } else {
            $fullname = $user['full_name'];
        }

        if (isset($_POST['username'])) {
            $username = trim(htmlspecialchars($_POST['username']));
        } else {
            $username = $user['username'];
        }

        if (isset($_POST['bio'])) {
            $bio = trim(htmlspecialchars($_POST['bio']));
            $bio = preg_replace('/\s+/', ' ', $bio);
            $bio = html_entity_decode($bio);
        } else {
            $bio = null;
        }

        if (isset($_POST['notifications'])) {
            $notifications = trim(htmlspecialchars($_POST['notifications']));
            if ($notifications === "enable") {
                $notifications = true;
            } elseif ($notifications === "disable") {
                $notifications = false;
            } else {
                return ['code' => 400, 'message' => "Notifications must be enable or disable."];
            }
        } else {
            $notifications = $user['email_notifs'];
        }

        if ($user['full_name'] !== $fullname) {
            if (strlen($fullname) < 1 || strlen($fullname) > 30) {
                return ['code' => 400, 'message' => "Full name must be between 3 and 16 characters long."];
            }
        }

        if ($user['username'] !== $username) {
            $usernameCheck = checkUsername($username);
            if (!$usernameCheck['success']) {
                return ['code' => 400, 'message' => $usernameCheck['message']];
            }
            if (getUserByUsername($db, $username)) {
                return ['code' => 409, 'message' => "This username is already taken."];
            }
        }

        if ($user['bio'] !== $bio) {
            if (strlen($bio) > 150) {
                return ['code' => 400, 'message' => "Bio must be between 0 and 150 characters long."];
            }
        }

        if (isset($_FILES['avatar'])) {
            $avatar = $_FILES['avatar'];
            if ($avatar && $avatar['size'] > 0) {
                $avatarUpload = uploadAvatar($userId, $avatar);
                if ($avatarUpload['code'] !== 200) {
                    return ['code' => $avatarUpload['code'], 'message' => $avatarUpload['message']];
                }
                updateAvatar($db, $userId, $avatarUpload['file']);
            }
        }

        if ($user['full_name'] !== $fullname) {
            updateFullName($db, $userId, $fullname);
        }
        if ($user['username'] !== $username) {
            updateUsername($db, $userId, $username);
        }
        if ($user['bio'] !== $bio) {
            updateBio($db, $userId, $bio);
        }
        if ($user['email_notifs'] !== $notifications) {
            updateEmailNotifs($db, $userId, $notifications);
        }
        return ['code' => 200, 'message' => "Profile succesfully updated."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = submitData();
    http_response_code($response['code']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
