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
    

    $query = "SELECT post.*, user.username, TIMEDIFF(NOW(), post.created_at) AS time_diff
              FROM post
              JOIN user ON post.user_id = user.id
              ORDER BY post.created_at DESC
             ";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $posts = $stmt->fetchAll();
}
?>

<div class="feed">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="user">
                <img src="./assets/noavatar.png" alt="noavatar.png">
                <div class="text">
                    <span class="username"><?php echo $post['username']; ?></span>
                    <span class="time-diff">• <?php echo formatElapsedTime($post['time_diff']); ?></span>
                </div>
            </div>
            <img src="<?php echo $post['path']; ?>" alt="picture.png">
        </div>
    <?php endforeach; ?>
</div>
