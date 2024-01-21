<?php
if (!isAuth()) {
    header("Location: /login");
    exit();
}

$userId = $_SESSION['id'];
$user = getUserByUsername($db, $uriArray[1]);
$avatar = $user['avatar'] ? "uploads/avatars/" . $user['avatar'] : null;
$itsMe = $user['id'] === $userId ? true : false;
$posts = getUserPosts($db, $user['id']);
?>

<div class="profile">
    <div class="top">
        <?php if ($avatar): ?>
            <img src="<?php echo $avatar ?>" alt="avatar">
        <?php else: ?>
            <img src="assets/noavatar.png" alt="avatar">
        <?php endif; ?>
        <div class="top-right">
            <h1><?php echo $user['username']; ?></h1>
            <?php if ($itsMe): ?>
                <a href="/settings">
                    <button>Edit profile</button>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <hr>
    <div class="posts">
        <?php if (!$posts): ?>
            <h1>No Posts Yet</h1>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <a href="/">
                    <img src="<?= "uploads/posts/" . $post['file']; ?>" alt="<?= $post['file']; ?>">
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
