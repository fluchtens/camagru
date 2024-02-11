<?php
if (isAuth()) {
    header("Location: /");
    exit();
}
?>

<div class="auth">
    <div id="loginErrMsg" class="err-msg">
        <p id="loginErrMsgText"></p>
    </div>
    <div class="auth-form">
        <a class="main-link" href="/">
            <img src="<?= $baseUrl . "assets/camagru.png" ?>" alt="camagru.png">
        </a>
        <form id="loginForm">
            <div class="input-container">
                <input type="text" name="username" placeholder="Username" autocomplete="off" required>
                <input type="password" name="password" placeholder="Password" autocomplete="off" required>
            </div>
            <button type="submit">Sign up</button>
        </form>
    </div>
    <div class="auth-redir-msg">
        <p>Don't have an account? <a href="/accounts/signup">Sign up</a></p>
    </div>
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/auth.js" ?>"></script>
</div>
