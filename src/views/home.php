<?php
if (!isAuth()) {
    header("Location: /login");
    exit();
}
?>

<div class="main-container">
    <?php require "./views/partials/feed.php" ?>
</div>
