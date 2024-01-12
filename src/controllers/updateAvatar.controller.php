<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";

function checkFile($file, $type) {
    if (file_exists($file)) {
        return ['code' => 409, 'message' => "This file already exists."];
    }
    elseif ($_FILES["avatarToUpload"]["size"] > 500000) {
        return ['code' => 413, 'message' => "Your file is too large."];
    }
    elseif ($type != "png" && $type != "jpg" && $type != "jpeg" && $type != "gif" ) {
        return ['code' => 400, 'message' => "Only PNG, JPG, JPEG & GIF files are allowed."];
    }
    return ['code' => 200];
}

function uploadFile($file) {
    if (!move_uploaded_file($_FILES["avatarToUpload"]["tmp_name"], $file)) {
        return ['code' => 400, 'message' => "Sorry, there was an error uploading your file."];
    }
    return ['code' => 200];
}

function submitData($username) {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $userId = $_SESSION['id'];
        $db = connectToDatabase();

        $dir = "../uploads/avatars/";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $type = strtolower(pathinfo($_FILES["avatarToUpload"]["name"], PATHINFO_EXTENSION));
        $fileName = uniqid($userId . '_', true) . '.' . $type;
        $filePath = $dir . $fileName;
    
        $fileCheck = checkFile($filePath, $type);
        if ($fileCheck['code'] !== 200) {
            return ['code' => $fileCheck['code'], 'message' => $fileCheck['message']];
        }

        $fileUpload = uploadFile($filePath);
        if ($fileUpload['code'] !== 200) {
            return ['code' => $fileUpload['code'], 'message' => $fileUpload['message']];
        }

        updateAvatar($db, $userId, $fileName);
        return ['code' => 200, 'message' => "The file has been uploaded."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = submitData($newUsername);
    http_response_code($response['code']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>