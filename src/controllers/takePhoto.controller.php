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
        $caption = 'No caption';

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (!$data['image']) {
            return ['code' => 400, 'message' => "Missing image."];
        } elseif (!$data['filter']) {
            return ['code' => 400, 'message' => "Missing filter."];
        }

        $filter = getFilter($db, $data['filter']);
        if (!$filter) {
            return ['code' => 400, 'message' => "Invalid filter."];
        }

        $imageData = str_replace('data:image/png;base64,', '', $data['image']);
        $decodedImageData = base64_decode($imageData);
        $imageCheck = checkImage($decodedImageData);
        if ($imageCheck['code'] !== 200) {
            return ['code' => $imageCheck['code'], 'message' => $imageCheck['message']];
        }

        $uploadDir = createUploadDir();
        $fileName = uniqid($userId . '_', true) . '.png';
        $filePath = $uploadDir . $fileName;

        file_put_contents($filePath, $decodedImageData);
        applyFilter($filePath, $filter['file']);
        createPost($db, $userId, $caption, $fileName);

        return ['code' => 200, 'message' => "The photo has been successfully saved."];
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
