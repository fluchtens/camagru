<?php
session_start();

require "../../core/database.php";
require "../../models/post.model.php";

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $postId = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$postId) {
            return ['code' => 400, 'message' => "Id cannot be empty."];
        }

        $db = connectToDatabase();
        $userId = $_SESSION['id'];

        $post = getUserPostById($db, $userId, $postId);
        if (!$post) {
            return ['code' => 404, 'message' => "This post does not exist or has been removed."];
        }

        return ['code' => 200, 'post' => $post];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $response = submitData();
    http_response_code($response['code']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
