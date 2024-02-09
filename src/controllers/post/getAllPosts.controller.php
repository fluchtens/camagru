<?php
session_start();

require "../../core/database.php";

function getPaginatedPosts($db, $userId, $page = 1, $perPage = 5) {
    $offset = ($page - 1) * $perPage;

    $query = "SELECT 
                post.*,
                user.username AS user_username, 
                user.avatar AS user_avatar, 
                IF(post_like.id IS NOT NULL, 1, 0) AS liked,
                TIMEDIFF(NOW(), post.created_at) AS time_diff,
                (SELECT COUNT(*) FROM post_like WHERE post_id = post.id) AS like_count,
                (SELECT COUNT(*) FROM post_comment WHERE post_id = post.id) AS comment_count
            FROM post
            INNER JOIN user ON post.user_id = user.id
            LEFT JOIN post_like ON post.id = post_like.post_id AND post_like.user_id = :user_id
            WHERE post.published = 1
            ORDER BY post.created_at DESC
            LIMIT :offset, :perPage
    ";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ($posts ? $posts : null);
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $db = connectToDatabase();
    $userId = $_SESSION['id'];
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $perPage = 5;
    $posts = getPaginatedPosts($db, $userId, $page, $perPage);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($posts);
    exit();
}
?>
