<?php
if (!isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}

require "./controllers/updateProfile.controller.php";
?>

<div class="settings">
    <h1>Edit profile</h1>
    <?php if ($error): ?>
        <div class="auth-err-msg">
            <p><?php echo $error; ?></p>
        </div>
        <?php endif; ?>
        <form class="options" method="POST" action="">
            <div class="data">
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="Username" autocomplete="off">
            </div>
            <div class="data">
                <label for="avatar">Avatar</label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <button type="submit">Submit</button>
        </form> 
</div>

