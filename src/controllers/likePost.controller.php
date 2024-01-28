<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";
require "../models/post.model.php";

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $postId = trim(htmlspecialchars($_POST['post_id']));
        if (empty($postId)) {
            return ['code' => 401, 'message' => "Post id cannot be empty."];
        }

        $userId = $_SESSION['id'];
        $db = connectToDatabase();

        $post = getPostLiked($db, $userId, $postId);
        if ($post) {
            return ['code' => 400, 'message' => "You have already liked this post."];
        }

        likePost($db, $userId, $postId);
        return ['code' => 200, 'message' => "You have successfully liked the post." . $type];
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
