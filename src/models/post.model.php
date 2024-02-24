<?php
/* ************************************************************************** */
/*                                   SELECT                                   */
/* ************************************************************************** */

function getPostById($db, $postId) {
    $query = "SELECT *
            FROM post
            WHERE id = :postId AND published = 1
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($post ? $post : null);
}

function getWaitingPostById($db, $postId) {
    $query = "SELECT *
            FROM post
            WHERE id = :postId AND published = 0
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($post ? $post : null);
}

function getUserPostById($db, $userId, $postId) {
    $query = "SELECT
                post.*,
                TIMEDIFF(NOW(), post.created_at) AS time_diff,
                user.username AS user_username,
                user.avatar AS user_avatar,
                IF(post_like.id IS NOT NULL, 1, 0) AS liked,
                (post.user_id = :userId) AS deletable,
                (SELECT COUNT(*) FROM post_like WHERE post_id = post.id) AS like_count,
                (SELECT COUNT(*) FROM post_comment WHERE post_id = post.id) AS comment_count
            FROM post
            INNER JOIN user ON post.user_id = user.id
            LEFT JOIN post_like ON post.id = post_like.post_id AND post_like.user_id = :userId
            WHERE post.id = :postId AND post.published = 1
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($post ? $post : null);
}

function getPosts($db, $userId, $page = 1, $perPage = 5) {
    $offset = ($page - 1) * $perPage;
    $query = "SELECT 
                post.*,
                TIMEDIFF(NOW(), post.created_at) AS time_diff,
                user.username AS user_username, 
                user.avatar AS user_avatar, 
                IF(post_like.id IS NOT NULL, 1, 0) AS liked,
                (SELECT COUNT(*) FROM post_like WHERE post_id = post.id) AS like_count,
                (SELECT COUNT(*) FROM post_comment WHERE post_id = post.id) AS comment_count
            FROM post
            INNER JOIN user ON post.user_id = user.id
            LEFT JOIN post_like ON post.id = post_like.post_id AND post_like.user_id = :userId
            WHERE post.published = 1
            ORDER BY post.created_at DESC
            LIMIT :offset, :perPage
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ($posts ? $posts : null);
}

function getUserProfilePosts($db, $userId) {
    $query = "SELECT
                post.*,
                TIMEDIFF(NOW(), post.created_at) AS time_diff,
                user.id AS user_id,
                (SELECT COUNT(*) FROM post_like WHERE post_id = post.id) AS like_count,
                (SELECT COUNT(*) FROM post_comment WHERE post_id = post.id) AS comment_count
            FROM post
            INNER JOIN user ON post.user_id = user.id
            WHERE user.id = :userId AND post.published = 1
            ORDER BY post.created_at DESC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll();
    return ($posts ? $posts : null);
}

function getUserWaitingPosts($db, $userId) {
    $query = "SELECT * FROM post
            WHERE user_id = :userId AND published = 0
            ORDER BY post.created_at DESC
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ($posts ? $posts : null);
}

/* ************************************************************************** */
/*                                   UPDATE                                   */
/* ************************************************************************** */

function publishUserWaitingPosts($db, $userId) {
    $query = "UPDATE post
            SET published = 1
            WHERE user_id = :userId AND published = 0
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

/* ************************************************************************** */
/*                                INSERT INTO                                 */
/* ************************************************************************** */

function createPost($db, $userId, $caption, $file) {
    $query = "INSERT INTO post (user_id, caption, file)
            VALUES (:userId, :caption, :file)
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':caption', $caption, PDO::PARAM_STR);
    $stmt->bindParam(':file', $file, PDO::PARAM_STR);
    $stmt->execute();
}

/* ************************************************************************** */
/*                                   DELETE                                   */
/* ************************************************************************** */

function deletePostById($db, $postId) {
    $query = "DELETE FROM post
            WHERE id = :postId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
    $stmt->execute();
}
?>
