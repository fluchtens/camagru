<?php
session_start();

require "../core/database.php";
require "../models/post.model.php";
require "../models/filter.model.php";

function checkImage($image) {
    $mime = explode('/', finfo_buffer(finfo_open(), $image, FILEINFO_MIME_TYPE))[1];
    if (strlen($image) === 0) {
        return ['code' => 400, 'message' => "The image is empty."];
    }
    elseif (strlen($image) > 5 * 1024 * 1024) {
        return ['code' => 413, 'message' => "Your file is too large."];
    }
    elseif ($mime != "png") {
        return ['code' => 400, 'message' => "Invalid file type. Only PNG files are allowed."];
    }
    return ['code' => 200];
}

function createUploadDir() {
    $uploadDir = "../assets/uploads/posts/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    return ($uploadDir);
}

function applyFilter($imagePath, $filterPath) {
    $baseImage = imagecreatefrompng($imagePath);
    $baseWidth = imagesx($baseImage);
    $baseHeight = imagesy($baseImage);
    
    $filterImage = imagecreatefrompng("../assets/filters/" . $filterPath);
    $filterWidth = imagesx($filterImage);
    $filterHeight = imagesy($filterImage);
    
    $positionX = ($baseWidth - $filterWidth) / 2;
    $positionY = ($baseHeight - $filterHeight) / 2;
    
    imagesavealpha($baseImage, true);
    imagecopy($baseImage, $filterImage, $positionX, $positionY, 0, 0, $filterWidth, $filterHeight);
    imagepng($baseImage, $imagePath);

    imagedestroy($baseImage);
    imagedestroy($filterImage);
}

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $db = connectToDatabase();
        $userId = $_SESSION['id'];

        $waitingPosts = getUserWaitingPosts($db, $userId);
        if (!$waitingPosts) {
            return ['code' => 400, 'message' => "You have no saved images."];
        }

        publishUserWaitingPosts($db, $userId);
        return ['code' => 200, 'message' => "Your saved images have been successfully published."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = submitData();
    http_response_code($response['code']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
