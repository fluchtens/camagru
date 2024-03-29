<?php
function getPostComments($db, $postId) {
    $query = "SELECT
                post_comment.*,
                TIMEDIFF(NOW(), created_at) AS time_diff,
                user.username as user_username, 
                user.avatar as user_avatar
            FROM post_comment
            INNER JOIN user ON post_comment.user_id = user.id
            WHERE post_id = :post_id
            ORDER BY created_at DESC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ($comments ? $comments : null);
}

function commentPost($db, $userId, $postId, $comment) {
    $query = "INSERT INTO post_comment (user_id, post_id, comment)
            VALUES (:user_id, :post_id, :comment)
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam('user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam('post_id', $postId, PDO::PARAM_INT);
    $stmt->bindParam('comment', $comment, PDO::PARAM_STR);
    $stmt->execute();
}

function deletePostComments($db, $postId) {
    $query = "DELETE FROM post_comment WHERE post_id = :postId";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
    $stmt->execute();
}
?>