<?php
session_start();

require "../../core/database.php";
require "../../core/sendEmail.php";
require "../../models/user.model.php";
require "../../models/post.model.php";
require "../../models/comment.model.php";

function checkPostId($db, $postId) {
    if (!$postId) {
        return ['code' => 400, 'message' => "Post id cannot be empty."];
    }
    elseif (!getPostById($db, $postId)) {
        return ['code' => 404, 'message' => "This post does not exist or has been removed."];
    }
    return ['code' => 200];
}

function checkComment($comment) {
    if (!$comment) {
        return ['code' => 400, 'message' => "Comment cannot be empty."];
    }
    elseif (strlen($comment) > 2200) {
        return ['code' => 400, 'message' => "Comment is too long. Maximum length is 2200 characters."];
    }
    return ['code' => 200];
}

function sendNotifEmail($username, $comment, $authorFullName, $authorEmail) {
    $mailSubject = "New comment on your publication";
    $mailBody = "
        <div style='max-width: 640px; margin: 0 auto; text-align: center'>
            <p>Hi <strong>$authorFullName</strong>,</p>
            <p>You have received a new comment from <strong>$username</strong> on your publication:</p>
            <p><strong>&ldquo;$comment&rdquo;</strong></p>
        </div>
    ";
    sendEmail($authorEmail, $mailSubject, $mailBody);
}

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        $postId = isset($data['post_id']) ? trim(htmlspecialchars($data['post_id'])) : null;
        if (isset($data['comment'])) {
            $comment = htmlspecialchars($data['comment']);
            $comment = preg_replace('/\s+/', ' ', $comment);
            $comment = html_entity_decode($comment);
        } else {
            $comment = null;
        }

        $userId = $_SESSION['id'];
        $db = connectToDatabase();

        $postIdCheck = checkPostId($db, $postId);
        if ($postIdCheck['code'] !== 200) {
            return ['code' => $postIdCheck['code'], 'message' => $postIdCheck['message']];
        }

        $commentCheck = checkComment($comment);
        if ($commentCheck['code'] !== 200) {
            return ['code' => $commentCheck['code'], 'message' => $commentCheck['message']];
        }

        $user = getUserById($db, $userId);
        $username = $user['username'];
        $post = getPostById($db, $postId);
        $author = getUserById($db, $post['user_id']);
        $authorFullName = $author['full_name'];
        $authorEmail = $author['email'];

        commentPost($db, $userId, $postId, $comment);
        if ($author['email_notifs']) {
            sendNotifEmail($username, $comment, $authorFullName, $authorEmail);
        }
        return ['code' => 200, 'message' => "Comment sent succesfully."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = submitData();
    http_response_code($response['code']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
