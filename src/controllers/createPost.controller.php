<?php
require "../core/database.php";

session_start();
$data = json_decode(file_get_contents("php://input"));
if ($data && isset($data->image) && isset($_SESSION['id'])) {
    try {
        $userId = $_SESSION['id'];
        $caption = 'No caption';

        $uploadsDir = "../uploads/";
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $imageData = str_replace('data:image/png;base64,', '', $data->image);
        $decodedImageData = base64_decode($imageData);
        $filePath = $uploadsDir . $userId . '_' . uniqid() . '.png';
    
        file_put_contents($filePath, $decodedImageData);
    
        $db = connectToDatabase();
        $query = "INSERT INTO post (user_id, caption, path) VALUES (:user_id, :caption, :path)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':caption', $caption);
        $stmt->bindParam(':path', $filePath);
        $stmt->execute();
    
        echo $filePath;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Failed to save image." . $e->getMessage()]);
    }
}
?>
