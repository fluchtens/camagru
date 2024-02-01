<?php
if (!isAuth()) {
    header("Location: /login");
    exit();
}

$post = getPostById($db, $uriArray[2]);
if ($post) {
    $image = $baseUrl . "assets/uploads/posts/" . $post['file'];
    $comments = getPostComments($db, $uriArray[2]);
}
?>

<div id="commentsModal" class="comments-modal">
    <div class="post">
        <div class="header">
            <h1>Comments</h1>
            <button id="closeCommentsModalBtn">
                <img src="<?= $baseUrl . "assets/cross.png" ?>" alt="cross.png">
            </button>
        </div>
        <hr>
        <div class="comments">
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
        <input type="text" placeholder="Add a comment..">
    </div>
    <script src="<?= $baseUrl . "scripts/comments.js" ?>"></script>
</div>