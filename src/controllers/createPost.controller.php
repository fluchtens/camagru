<?php
session_start();

require "../core/database.php";
require "../models/post.model.php";

function applyFilter($imagePath) {
    $baseImage = imagecreatefrompng($imagePath);
    $baseWidth = imagesx($baseImage);
    $baseHeight = imagesy($baseImage);

    $filterImage = imagecreatefrompng("../assets/filters/fire.png");
    $filterWidth = imagesx($filterImage);
    $filterHeight = imagesy($filterImage);

    $positionX = ($baseWidth - $filterWidth) / 2;
    $positionY = ($baseHeight - $filterHeight) / 2;

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

        $uploadsDir = "../uploads/posts/";
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $data = json_decode(file_get_contents("php://input"));
        $imageData = str_replace('data:image/png;base64,', '', $data->image);
        $decodedImageData = base64_decode($imageData);
        if (strlen($decodedImageData) === 0) {
            return ['code' => 400, 'message' => "The image is empty."];
        }

        $fileName = uniqid($userId . '_', true) . '.png';
        $filePath = $uploadsDir . $fileName;

        
        file_put_contents($filePath, $decodedImageData);
        applyFilter($filePath);

        createPost($db, $userId, $caption, $fileName);
        return ['code' => 200, 'message' => "The post was successfully published." . $filePath];
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
