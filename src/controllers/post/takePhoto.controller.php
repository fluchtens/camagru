<?php
session_start();

require "../../core/database.php";
require "../../models/post.model.php";
require "../../models/filter.model.php";

function checkImage($image, $type) {
    if (strlen($image) === 0) {
        return ['code' => 400, 'message' => "The image is empty."];
    }
    elseif (strlen($image) > 1 * 1024 * 1024) {
        return ['code' => 400, 'message' => "Your image is too large (+1 mo)."];
    }
    elseif ($type !== "png" && $type !== "jpeg") {
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

    // Calculer le ratio d'aspect du filtre
    $filterAspectRatio = $filterWidth / $filterHeight;

    // Calculer la nouvelle hauteur du filtre en conservant le ratio d'aspect
    $newFilterHeight = $baseHeight;

    // Calculer la nouvelle largeur du filtre
    $newFilterWidth = $newFilterHeight * $filterAspectRatio;

    // Redimensionner le filtre
    $resizedFilter = imagescale($filterImage, $newFilterWidth, $newFilterHeight);

    // Positionner le filtre au centre de l'image de base
    $positionX = round(($baseWidth - $newFilterWidth) / 2);
    $positionY = round(($baseHeight - $newFilterHeight) / 2);

    // Appliquer le filtre redimensionné à l'image de base
    imagesavealpha($baseImage, true);
    imagecopy($baseImage, $resizedFilter, $positionX, $positionY, 0, 0, $newFilterWidth, $newFilterHeight);
    imagepng($baseImage, $imagePath);

    // Libérer les ressources
    imagedestroy($baseImage);
    imagedestroy($filterImage);
    imagedestroy($resizedFilter);
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

        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageBase64);
        // $imageData = str_replace('data:image/png;base64,', '', $imageBase64);
        $decodedImageData = base64_decode($imageData);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = explode('/', finfo_buffer($finfo, $decodedImageData))[1];
        finfo_close($finfo);


        $imageCheck = checkImage($decodedImageData, $mime);
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
