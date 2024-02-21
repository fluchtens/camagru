<?php
session_start();

require "../../core/database.php";
require "../../models/post.model.php";

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $db = connectToDatabase();
        $userId = $_SESSION['id'];

        $posts = getUserWaitingPosts($db, $userId);
        return ['code' => 200, 'posts' => $posts];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $response = submitData();
    http_response_code($response['code']);
    header('Content-Type: application/json');
    if ($response['code'] !== 200) {
        echo json_encode($response);
    } else {
        echo json_encode($response['posts']);
    }
    exit();
}
?>
