<?php
if (!isAuth()) {
    header("Location: /login");
    exit();
}

$post = getPostById($db, $uriArray[2]);
if ($post) {
    $image = $baseUrl . "uploads/posts/" . $post['file'];
}
?>

<div class="post">
    <img src="<?= $image ?>" alt="<?= $post['file'] ?>">
</div>