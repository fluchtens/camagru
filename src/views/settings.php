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
        <div class="err-msg">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>
    <form class="options" method="POST" action="" enctype="multipart/form-data">
        <div class="data">
            <label for="newUsername">Username</label>
            <input type="text" name="newUsername" value=<?php echo $user['username']; ?> placeholder="Username" autocomplete="off">
        </div>
        <div class="data">
            <label for="avatarToUpload">Avatar</label>
            <input type="file" name="avatarToUpload">
        </div>
        <button type="submit">Submit</button>
    </form> 
</div>

