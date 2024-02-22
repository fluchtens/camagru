<?php
if (isAuth()) {
    header("Location: /");
    exit();
}
?>

<div class="reset">
    <h1>Create a new password</h1>
    <div id="msg" class="msg">
        <p id="msgText"></p>
    </div>
    <form id="resetPasswordForm">
        <div class="data">
            <label for="new-password">New Password</label>
            <input type="password" id="new-password" name="new-password" placeholder="New password" autocomplete="off" required>
        </div>
        <div class="data">
            <label for="confirm-new-password">New password confirmation</label>
            <input type="password" id="confirm-new-password" name="confirm-new-password" placeholder="New password confirmation" autocomplete="off" required>
        </div>
        <button type="submit">Submit</button>
    </form>
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/accounts/password/resetPassword.js" ?>"></script>
    <script src="<?= $baseUrl . "scripts/api.js" ?>"></script>
</div>
