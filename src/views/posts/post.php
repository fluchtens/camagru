<?php
if (!isAuth()) {
    header("Location: /accounts/login");
    exit();
}

$postId = $uriArray[2];
?>

<div id="post" class="post" data-post-id="<?= $postId ?>">
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/posts/post.js" ?>"></script>
    <script src="<?= $baseUrl . "scripts/comments.js" ?>"></script>
    <script src="<?= $baseUrl . "scripts/api.js" ?>"></script>
    <script src="<?= $baseUrl . "scripts/utils.js" ?>"></script>
</div>
