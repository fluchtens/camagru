<?php
session_start();

require "../../core/database.php";
require "../../models/post.model.php";

function submitData() {
    try {
        $db = connectToDatabase();
        $userId = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $posts = getPosts($db, $userId, $page);
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
