<?php
if (!isAuth()) {
    header("Location: /login");
    exit();
}

$user = getUserByUsername($db, $uriArray[1]);
$avatar = $user['avatar'] ? $baseUrl . "uploads/avatars/" . $user['avatar'] : null;
$itsMe = $user['id'] === $userId ? true : false;
$posts = getUserPosts($db, $user['id']);
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
            <span>Cristiano Ronaldo</span>
            <p>Join my NFT journey on @Binance. Click the link below to get started.</p>
            <?php if ($itsMe): ?>
                <a href="/settings">Edit profile</a>
            <?php endif; ?>
        </div>
    </div>
    <hr>
    <div class="posts">
        <?php if (!$posts): ?>
            <h1>No Posts Yet</h1>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <a href="<?= "p/" . $post['id'] ?>">
                    <img src="<?= $baseUrl . "uploads/posts/" . $post['file']; ?>" alt="<?= $post['file']; ?>">
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
