<?php
session_start();

require "../core/database.php";
require "../models/post.model.php";
require "../models/comment.model.php";

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $postId = htmlspecialchars($_GET['post_id']);
        if (!$postId) {
            return ['code' => 400, 'message' => "Post id cannot be empty."];
        }

        $db = connectToDatabase();

        if (!getPostById($db, $postId)) {
            return ['code' => 404, 'message' => "This post does not exist or has been removed."];
        }

        $comments = getPostComments($db, $postId);
        return ['code' => 200, 'comments' => $comments];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $response = submitData();
    http_response_code($response['code']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
