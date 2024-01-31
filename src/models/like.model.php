<?php
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