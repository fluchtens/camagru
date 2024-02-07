<?php
if (isAuth()) {
    header("Location: /");
    exit();
}
?>

<div class="pwd-reset">
    <div id="msg" class="msg">
        <p id="msgText"></p>
    </div>
    <form id="resetPasswordForm">
        <a class="main-link" href="<?= $baseUrl ?>">
            <img src="<?= $baseUrl . "assets/camagru.png" ?>" alt="camagru.png">
        </a>
        <h1>Trouble logging in?</h1>
        <p>Enter your email and we'll send you a link to get back into your account.</p>
        <input id="emailInput" type="email" name="email" placeholder="Email" autocomplete="off" required>
        <button id="submitBtn" type="submit" disabled>Send login link</button>
    </form>
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/resetPassword.js" ?>"></script>
</div>
