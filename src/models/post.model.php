<?php
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
    $query = "SELECT post.*, user.username, TIMEDIFF(NOW(), post.created_at) AS time_diff
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
