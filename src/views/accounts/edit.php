<?php
if (!isAuth()) {
    header("Location: /accounts/login");
    exit();
}

$user = getUserById($db, $userId);
?>

<div class="settings">
    <div class="editProfile">
        <h1>Edit profile</h1>
        <div id="editProfileMsg" class="msg">
            <p id="editProfileMsgText"></p>
        </div>
        <form id="editProfileForm">
            <div class="data">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" value="<?= $user['full_name']; ?>" placeholder="Full Name" autocomplete="off">
            </div>
            <div class="data">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= $user['username']; ?>" placeholder="Username" autocomplete="off">
            </div>
            <div class="data">
                <label for="bio">Bio</label>
                <input type="text" id="bio" name="bio" value="<?= $user['bio']; ?>" placeholder="Bio" autocomplete="off">
            </div>
            <div class="data">
                <label for="avatar">Avatar</label>
                <input type="file" id="avatar" name="avatar" accept=".jpg, .jpeg, .png, .gif">
            </div>
            <div class="data">
                <legend>Comment notifications by email</legend>
                <div class="email-notifs">
                    <div>
                        <input type="radio" id="enableNotifications" name="notifications" value="enable" <?= $user['email_notifs'] ? 'checked' : ''; ?>>
                        <label for="enableNotifications">Enable</label>
                    </div>
                    <div>
                        <input type="radio" id="disableNotifications" name="notifications" value="disable" <?= !$user['email_notifs'] ? 'checked' : ''; ?>>
                        <label for="disableNotifications">Disable</label>
                    </div>
                </div>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
    <div class="editPassword">
        <h1>Edit password</h1>
        <div id="editPasswordMsg" class="msg">
            <p id="editPasswordMsgText"></p>
        </div>
        <form id="editPasswordForm">
            <div class="data">
                <label for="old-password">Old password</label>
                <input type="password" id="old-password" name="old-password" placeholder="Old password" autocomplete="off" required>
            </div>
            <div class="data">
                <label for="new-password">New password</label>
                <input type="password" id="new-password" name="new-password" placeholder="New password" autocomplete="off" required>
            </div>
            <div class="data">
                <label for="confirm-new-password">Confirm new password</label>
                <input type="password" id="confirm-new-password" name="confirm-new-password" placeholder="Confirm new password" autocomplete="off" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/accounts/edit.js" ?>"></script>
    <script src="<?= $baseUrl . "scripts/api.js" ?>"></script>
</div>
