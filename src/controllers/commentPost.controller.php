<?php
session_start();

require "../core/database.php";
require "../models/post.model.php";
require "../models/comment.model.php";

function checkPostId($db, $postId) {
    if (!$postId) {
        return ['code' => 400, 'message' => "Post id cannot be empty."];
    }
    elseif (!getPostById($db, $postId)) {
        return ['code' => 404, 'message' => "This post does not exist or has been removed."];
    }
    return ['code' => 200];
}

function checkComment($comment) {
    if (!$comment) {
        return ['code' => 400, 'message' => "Comment cannot be empty."];
    }
    elseif (strlen($comment) > 2200) {
        return ['code' => 400, 'message' => "Comment is too long. Maximum length is 2200 characters."];
    }
    return ['code' => 200];
}

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        $userId = $_SESSION['id'];
        $db = connectToDatabase();

        $postId = $data['post_id'];
        $postIdCheck = checkPostId($db, $postId);
        if ($postIdCheck['code'] !== 200) {
            return ['code' => $postIdCheck['code'], 'message' => $postIdCheck['message']];
        }

        $comment = $data['comment'];
        $commentCheck = checkComment($comment);
        if ($commentCheck['code'] !== 200) {
            return ['code' => $commentCheck['code'], 'message' => $commentCheck['message']];
        }

        commentPost($db, $userId, $postId, $comment);
        return ['code' => 200, 'message' => "Comment sent succesfully."];
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
