<?php
if (!isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}
?>

<div class="settings">
    <h1>Edit profile</h1>
        <div class="err-msg" id="errMsg">
            <p id="errMsgText"></p>
        </div>
    <form class="options" id="settingsForm">
        <div class="data">
            <label for="newUsername">Username</label>
            <input type="text" name="newUsername" value=<?php echo $user['username']; ?> placeholder="Username" autocomplete="off">
        </div>
        <div class="data">
            <label for="avatarToUpload">Avatar</label>
            <input type="file" name="avatarToUpload" accept=".jpg, .jpeg, .png, .gif">
        </div>
        <button type="submit">Submit</button>
    </form>
    <script src="scripts/updateProfile.js"></script>
</div>

