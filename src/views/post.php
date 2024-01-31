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

<div id="myModal" class="modal">
    <div class="post">
        <img src="<?= $image ?>" alt="<?= $post['file'] ?>">
        <div class="comments">
            <?php foreach($comments as $comment): ?>
                <div class="comment">
                    <?php $avatar = $comment['user_avatar'] ? $baseUrl . "assets/uploads/avatars/" . $comment['user_avatar'] : null; ?>
                    <?php if ($avatar): ?>
                        <img src="<?= $avatar; ?>" alt="<?= $comment['avatar']; ?>">
                    <?php else: ?>
                        <img src="<?= $baseUrl . "assets/noavatar.png"; ?>" alt="noavatar.png">
                    <?php endif; ?>
                    <p class="username"><?= $comment['user_username'] ?></p>
                    <p class="message"><?= $comment['comment'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>