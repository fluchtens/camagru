<?php
function getPostComments($db, $postId) {
    $query = "SELECT
                post_comment.*,
                user.username as user_username, 
                user.avatar as user_avatar
            FROM post_comment
            INNER JOIN user ON post_comment.user_id = user.id
            WHERE post_id = :post_id
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ($comments ? $comments : null);
}
?>