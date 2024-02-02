<?php
function getAllPosts($db, $userId) {
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
            ORDER BY post.created_at DESC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ($posts ? $posts : null);
}


function getPostById($db, $postId) {
    $query = "SELECT
                post.*,
                TIMEDIFF(NOW(), post.created_at) AS time_diff,
                user.username AS user_username,
                user.avatar AS user_avatar
            FROM post
            INNER JOIN user ON post.user_id = user.id
            WHERE post.id = :id
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($post ? $post : null);
}

function getUserPosts($db, $id) {
    $query = "SELECT post.*, user.id AS user_id, TIMEDIFF(NOW(), post.created_at) AS time_diff
            FROM post
            JOIN user ON post.user_id = user.id
            WHERE user.id = :id
            ORDER BY post.created_at DESC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $posts = $stmt->fetchAll();
    return ($posts ? $posts : null);
}

function createPost($db, $userId, $caption, $file) {
    $query = "INSERT INTO post (user_id, caption, file) VALUES (:user_id, :caption, :file)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':caption', $caption);
    $stmt->bindParam(':file', $file);
    $stmt->execute();
}
?>
