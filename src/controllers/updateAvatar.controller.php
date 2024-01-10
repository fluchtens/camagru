<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";
require "../core/utils.php";

function submitData($username) {
    try {
        $dir = "../uploads/avatar/";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

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

        if (move_uploaded_file($_FILES["avatarToUpload"]["tmp_name"], $file)) {
            return ['code' => 200, 'message' => "The file has been uploaded."];
        } else {
            return ['code' => 400, 'message' => "Sorry, there was an error uploading your file."];
        }
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