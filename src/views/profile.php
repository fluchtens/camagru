<?php
if (!isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}

$user = getUserByUsername($db, $uriArray[1]);
if ($user['avatar']) {
    $avatar = "uploads/avatar/" . $user['avatar'];
}
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
            <a href="/settings">
                <button>Edit profile</button>
            </a>
        </div>
    </div>
    <hr>
    <div class="posts">
        <?php if (!$posts): ?>
            <h1>No Posts Yet</h1>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <img src="<?php echo $post['path']; ?>" alt="picture.png">
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
