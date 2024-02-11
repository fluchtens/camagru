<?php
session_start();

require "../core/database.php";
require "../models/post.model.php";

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $db = connectToDatabase();
        $userId = $_SESSION['id'];

        $waitingPosts = getUserWaitingPosts($db, $userId);
        if (!$waitingPosts) {
            return ['code' => 400, 'message' => "You have no saved images."];
        }

        publishUserWaitingPosts($db, $userId);
        return ['code' => 200, 'message' => "Your saved images have been successfully published."];
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
