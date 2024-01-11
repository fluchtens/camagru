<?php
session_start();

require "../core/database.php";

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $db = connectToDatabase();
        $userId = $_SESSION['id'];
        $caption = 'No caption';

        $uploadsDir = "../uploads/";
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $data = json_decode(file_get_contents("php://input"));
        $imageData = str_replace('data:image/png;base64,', '', $data->image);
        $decodedImageData = base64_decode($imageData);
        $filePath = $uploadsDir . $userId . '_' . uniqid() . '.png';
        file_put_contents($filePath, $decodedImageData);

        $query = "INSERT INTO post (user_id, caption, path) VALUES (:user_id, :caption, :path)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':caption', $caption);
        $stmt->bindParam(':path', $filePath);
        $stmt->execute();
        
        return ['code' => 200, 'message' => "The post was successfully published." . $imageDataURL . "caca"];
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
