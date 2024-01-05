<?php
require "./core/utils.php";

function submitData($username) {
    try {
        $db = connectToDatabase();
        $id = $_SESSION['id'];
    
        $usernameCheck = checkUsername($username);
        if (!$usernameCheck['success']) {
            return ['code' => 400, 'message' => $usernameCheck['message']];
        }
    
        if (getUserByUsername($db, $username)) {
            return ['code' => 409, 'message' => "This username is already taken."];
        }
    
        updateUsername($db, $id, $username);
        return ['code' => 200, 'message' => "Username succesfully updated."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

function updateAvatar() {
    try {
        $dir = "uploads/avatar/";
        $file = $dir . basename($_FILES["avatarToUpload"]["name"]);
        $type = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    
        if (file_exists($file)) {
            return ['code' => 409, 'message' => "This file already exists."];
        }

        if ($_FILES["avatarToUpload"]["size"] > 500000) {
            return ['code' => 413, 'message' => "Your file is too large."];
        }

        if ($type != "png" && $type != "jpg" && $type != "jpeg" && $type != "gif" ) {
            return ['code' => 400, 'message' => "Only PNG, JPG, JPEG & GIF files are allowed."];
        }

        return ['code' => 200, 'message' => "Avatar succesfully updated."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

$db = connectToDatabase();
$id = $_SESSION['id'];
$user = getUserById($db, $id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = updateAvatar();
    http_response_code($response['code']);
    if ($response['code'] !== 200) {
        $error = $response['message'];
    } else {
        $user = getUserById($db, $id);
    }

    // $newUsername = htmlspecialchars($_POST['newUsername']);
    // $response = submitData($newUsername);
    // http_response_code($response['code']);
    // if ($response['code'] !== 200) {
    //     $error = $response['message'];
    // } else {
    //     $user = getUserById($db, $id);
    //     header("Location: /");
    //     exit();
    // }
}
?>