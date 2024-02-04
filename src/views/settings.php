<?php
if (!isAuth()) {
    header("Location: /login");
    exit();
}

$user = getUserById($db, $userId);
?>

<div class="settings">
    <h1>Edit profile</h1>
        <div class="err-msg" id="errMsg">
            <p id="errMsgText"></p>
        </div>
    <form class="options" id="settingsForm">
        <div class="data">
            <label for="fullname">Full Name</label>
            <input type="text" name="fullname" value="<?= $user['full_name']; ?>" placeholder="Full Name" autocomplete="off">
        </div>
        <div class="data">
            <label for="username">Username</label>
            <input type="text" name="username" value="<?= $user['username']; ?>" placeholder="Username" autocomplete="off">
        </div>
        <div class="data">
            <label for="username">Bio</label>
            <input type="text" name="bio" value="<?= $user['bio']; ?>" placeholder="Bio" autocomplete="off">
        </div>
        <div class="data">
            <label for="avatarToUpload">Avatar</label>
            <input type="file" name="avatarToUpload" accept=".jpg, .jpeg, .png, .gif">
        </div>
        <button type="submit">Submit</button>
    </form>
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/settings.js" ?>"></script>
</div>

