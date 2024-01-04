<?php
// require "./core/database.php";
// require "./models/user.model.php";
require "./models/post.model.php";

if (!isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}

$uri = $_SERVER["REQUEST_URI"];
$uriArray = explode('/', rtrim($uri, '/'));
$db = connectToDatabase();
$user = getUserByUsername($db, $uriArray[1]);
$posts = getUserPosts($db, $user['id']);
?>

<div class="profile">
    <div class="top">
        <img src="./assets/noavatar.png" alt="avatar.png">
        <div class="top-right">
            <h1><?php echo $user['username']; ?></h1>
            <button>Edit profile</button>
        </div>
    </div>
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
