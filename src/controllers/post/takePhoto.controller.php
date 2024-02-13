<?php
session_start();

require "../../core/database.php";
require "../../models/post.model.php";
require "../../models/filter.model.php";

function checkImage($image) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = explode('/', finfo_buffer($finfo, $image))[1];
    finfo_close($finfo);

    if (strlen($image) === 0) {
        return ['code' => 400, 'message' => "The image is empty."];
    }
    elseif (strlen($image) > 1 * 1024 * 1024) {
        return ['code' => 413, 'message' => "Your file is too large."];
    }
    elseif ($mime !== "png" || $mime !== "jpeg") {
        return ['code' => 400, 'message' => "Only PNG, JPG, JPEG & GIF files are allowed."];
    }
    return ['code' => 200];
}

function createUploadDir() {
    $uploadDir = "../../assets/uploads/posts/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    return ($uploadDir);
}

function applyFilter($imagePath, $filterPath) {
    $baseImage = imagecreatefrompng($imagePath);
    $baseWidth = imagesx($baseImage);
    $baseHeight = imagesy($baseImage);
    
    $filterImage = imagecreatefrompng("../../assets/filters/" . $filterPath);
    $filterWidth = imagesx($filterImage);
    $filterHeight = imagesy($filterImage);
    
    $positionX = round(($baseWidth - $filterWidth) / 2);
    $positionY = round(($baseHeight - $filterHeight) / 2);
    
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

        $imageBase64 = isset($data['image']) ? $data['image'] : null;
        if (!$imageBase64) {
            return ['code' => 400, 'message' => "Image cannot be empty."];
        }

        $filterId = isset($data['filter']) ? $data['filter'] : null;
        if (!$filterId) {
            return ['code' => 400, 'message' => "Filter cannot be empty."];
        }

        $filter = getFilterById($db, $filterId);
        if (!$filter) {
            return ['code' => 400, 'message' => "Invalid filter."];
        }

        // $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // $mime = finfo_buffer($finfo, base64_decode($imageBase64));
        // finfo_close($finfo);
        // return ['code' => 400, 'message' => $mime];

        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageBase64);
        // $imageData = str_replace('data:image/png;base64,', '', $imageBase64);
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
