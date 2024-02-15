<?php
if (!isAuth()) {
    header("Location: /accounts/login");
    exit();
}

$username = $uriArray[1];
$user = getUserByUsername($db, $username);
$avatar = $user['avatar'] ? $baseUrl . "assets/uploads/avatars/" . $user['avatar'] : null;
$itsMe = $user['id'] === $userId ? true : false;
$posts = getUserProfilePosts($db, $user['id']);
?>

<div class="profile">
    <div class="top">
        <?php if ($avatar): ?>
            <img src="<?= $avatar ?>" alt="<?= $user['avatar'] ?>">
        <?php else: ?>
            <img src="<?= $baseUrl . "assets/noavatar.png" ?>" alt="noavatar.png">
        <?php endif; ?>
        <div class="top-right">
            <h1><?= $user['username']; ?></h1>
            <span><?= $user['full_name']; ?></span>
            <p><?= $user['bio']; ?></p>
            <?php if ($itsMe): ?>
                <a href="/accounts/edit">Edit profile</a>
            <?php endif; ?>
        </div>
    </div>
    <hr>
    <div class="posts">
        <?php if (!$posts): ?>
            <h1>No Posts Yet</h1>
        <?php else: ?>
            <?php $count = count($posts); ?>
            <?php for ($i = 0; $i < $count; $i += 3): ?>
                <div class="post-group">
                    <?php for ($j = $i; $j < min($i + 3, $count); $j++): ?>
                        <?php $post = $posts[$j]; ?>
                        <a href="<?= "/p/" . $post['id'] ?>">
                            <img src="<?= $baseUrl . "assets/uploads/posts/" . $post['file']; ?>" alt="<?= $post['file']; ?>">
                            <div class="stats">
                                <div class="stat">
                                    <img class="icon" src="<?= $baseUrl . "assets/like.png"; ?>" alt="comment.png">
                                    <span class="like-count"><?= $post['like_count']; ?></span>
                                </div>
                                <div class="stat">
                                    <img class="icon" src="<?= $baseUrl . "assets/comment.png"; ?>" alt="comment.png">
                                    <span class="comment-count"><?= $post['comment_count']; ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</div>
