<?php
function formatElapsedTime($timeDiff) {
    $timeComponents = explode(':', $timeDiff);
    $hours = $timeComponents[0];
    $minutes = $timeComponents[1];
    $seconds = $timeComponents[2];    
    $weeks = floor($hours / 24 / 7);
    $days = floor($hours / 24) % 7;

    if ($weeks > 0) {
        return $weeks . 'w';
    } elseif ($days > 0) {
        return $days . 'd';
    } elseif ($hours > 0) {
        return $hours . 'h';
    } elseif ($minutes > 0) {
        return $minutes . 'min';
    } elseif ($seconds > 0) {
        return $seconds . 's';
    } else {
        return 'Now';
    }
}

$posts = getAllPosts($db);
?>

<div class="feed">
    <?php if (!$posts): ?>
        <h1>No Posts Yet</h1>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <a class="user" href="<?= "/" . $post['username']; ?>">
                    <?php $avatar = $post['avatar'] ? $baseUrl . "uploads/avatars/" . $post['avatar'] : null; ?>
                    <?php if ($avatar): ?>
                        <img src="<?= $avatar; ?>" alt="<?= $post['avatar']; ?>">
                    <?php else: ?>
                        <img src="<?= $baseUrl . "assets/noavatar.png"; ?>" alt="noavatar.png">
                    <?php endif; ?>
                    <div class="text">
                        <p class="username"><?= $post['username']; ?></p>
                        <span class="time-diff">• <?= formatElapsedTime($post['time_diff']); ?></span>
                    </div>
                </a>
                <img src="<?= $baseUrl . "uploads/posts/" . $post['file']; ?>" alt="<?= $post['file']; ?>">
            </div>
        <?php endforeach; ?>
    <? endif; ?>
</div>
