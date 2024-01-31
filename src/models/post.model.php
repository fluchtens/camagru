<?php
function getAllPosts($db, $userId) {
    $query = "SELECT 
                post.*, 
                user.username as user_username, 
                user.avatar as user_avatar, 
                IF(post_like.post_id IS NOT NULL, 1, 0) AS liked,
                TIMEDIFF(NOW(), post.created_at) AS time_diff,
                (SELECT COUNT(*) FROM post_like WHERE post_id = post.id) AS like_count
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
    $query = "SELECT * FROM post WHERE id = :id";
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

function getPostLiked($db, $userId, $postId) {
    $query = "SELECT * FROM post_like WHERE user_id = :user_id AND post_id = :post_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($post ? $post : null);
}

function likePost($db, $userId, $postId) {
    $query = "INSERT INTO post_like (user_id, post_id) VALUES (:user_id, :post_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();
}

function unlikePost($db, $userId, $postId) {
    $query = "DELETE FROM post_like WHERE user_id = :user_id AND post_id = :post_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();
}
?>
