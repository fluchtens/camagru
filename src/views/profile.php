<?php
require "./core/database.php";
require "./models/user.model.php";
require "./models/post.model.php";

if (!isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}

$db = connectToDatabase();
$id = $_SESSION['id'];
$user = getUserById($db, $id);
$posts = getUserPosts($db, $id);
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
        <!-- <h1>No Posts Yet</h1> -->
        <?php foreach ($posts as $post): ?>
            <img src="<?php echo $post['path']; ?>" alt="picture.png">
        <?php endforeach; ?>
    </div>
</div>
