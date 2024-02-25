<?php
session_start();

require "../../core/database.php";
require "../../models/post.model.php";
require "../../models/like.model.php";
require "../../models/comment.model.php";

function deleteFile($file) {
    $filePath = "../../assets/uploads/posts/" . $file;
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        $postId = isset($data['post_id']) ? htmlspecialchars($data['post_id']) : null;
        if (!$postId) {
            return ['code' => 400, 'message' => "Post id cannot be empty."];
        }

        $userId = $_SESSION['id'];
        $db = connectToDatabase();

        $post = getPostById($db, $postId);
        if (!$post) {
            return ['code' => 404, 'message' => "This post does not exist or has been removed."];
        }

        if ($userId !== $post['user_id']) {
            return ['code' => 401, 'message' => "You are not the owner of this post."];
        }

        deletePostLikes($db, $postId);
        deletePostComments($db, $postId);
        deletePostById($db, $postId);
        deleteFile($post['file']);
        return ['code' => 200, 'message' => "You have successfully deleted the post." ];
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
