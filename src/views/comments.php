<?php
if (!isAuth()) {
    header("Location: /login");
    exit();
}

$postId = $uriArray[2];
$post = getPostById($db, $postId);
$comments = getPostComments($db, $postId);
$me = getUserById($db, $userId);
$myAvatar = $me['avatar'] ? $baseUrl . "assets/uploads/avatars/" . $me['avatar'] : null;
?>

<div id="commentsModal" class="comments-modal" data-post-id="<?= $post['id']; ?>">
    <div class="post">
        <div class="header">
            <h1>Comments</h1>
            <button id="closeCommentsModalBtn">
                <img src="<?= $baseUrl . "assets/cross.png" ?>" alt="cross.png">
            </button>
        </div>
        <hr>
        <div id="comments" class="comments">
            <?php if (!$comments): ?>
                <h2>No comments yet.</h2>
                <h3>Start the conversation.</h3>
            <?php else: ?>
                <?php foreach($comments as $comment): ?>
                    <div class="comment">
                        <?php $avatar = $comment['user_avatar'] ? $baseUrl . "assets/uploads/avatars/" . $comment['user_avatar'] : null; ?>
                        <?php if ($avatar): ?>
                            <img src="<?= $avatar; ?>" alt="<?= $comment['avatar']; ?>">
                        <?php else: ?>
                            <img src="<?= $baseUrl . "assets/noavatar.png"; ?>" alt="noavatar.png">
                        <?php endif; ?>
                        <div class="texts">
                            <div class="title">
                                <span class="username"><?= $comment['user_username'] ?></span>
                                <span class="time-diff">• <?= formatElapsedTime($comment['time_diff']); ?></span>
                            </div>
                            <p class="message"><?= $comment['comment'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <hr>
        <form id="commentForm" method="post">
            <?php if ($myAvatar): ?>
                <img src="<?= $myAvatar; ?>" alt="<?= $me['avatar'] ?>">
            <?php else: ?>
                <img src="<?= $baseUrl . "assets/noavatar.png"; ?>" alt="noavatar.png">
            <?php endif; ?>
            <input type="text" name="comment" placeholder="Add a comment.." autocomplete="off" required>
        </form>
    </div>
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/comments.js" ?>"></script>
</div>