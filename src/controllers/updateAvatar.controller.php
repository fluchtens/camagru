<?php
require "../core/database.php";

session_start();
$data = json_decode(file_get_contents("php://input"));
if ($data && isset($data->image) && isset($_SESSION['id'])) {
    try {
        echo "cacaacacccaaca";
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Failed to save image." . $e->getMessage()]);
    }
}
?>
