<?php
if (isset($_SESSION['id'])) {
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
}
?>

<div class="feed">
    <?php if (!$posts): ?>
        <h1>No Posts Yet</h1>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="user">
                    <img src="./assets/noavatar.png" alt="noavatar.png">
                    <div class="text">
                        <a class="username" href=<?php echo "/" . $post['username']?>><?php echo $post['username']; ?></a>
                        <span class="time-diff">• <?php echo formatElapsedTime($post['time_diff']); ?></span>
                    </div>
                </div>
                <img src="<?php echo $post['path']; ?>" alt="picture.png">
            </div>
        <?php endforeach; ?>
    <? endif; ?>
</div>
