<?php
function createPost($db, $userId, $caption, $file) {
    $query = "INSERT INTO post (user_id, caption, file) VALUES (:user_id, :caption, :file)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':caption', $caption);
    $stmt->bindParam(':file', $file);
    $stmt->execute();
}

function getUserPosts($db, $id) {
    $query = "SELECT *, TIMEDIFF(NOW(), post.created_at) AS time_diff
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

function getAllPosts($db) {
    $query = "SELECT post.*, user.username, user.avatar, TIMEDIFF(NOW(), post.created_at) AS time_diff
              FROM post
              JOIN user ON post.user_id = user.id
              ORDER BY post.created_at DESC
             ";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $posts = $stmt->fetchAll();
    return ($posts ? $posts : null);
}
?>
