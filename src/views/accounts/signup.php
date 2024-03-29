<?php
if (isAuth()) {
    header("Location: /");
    exit();
}
?>

<div class="auth">
    <div id="signupMsg" class="msg">
        <p id="signupMsgText"></p>
    </div>
    <div class="auth-form">
        <a class="main-link" href="/">
            <img src="<?= $baseUrl . "assets/camagru.png" ?>" alt="camagru.png">
        </a>
        <form id="registerForm">
            <div class="input-container">
                <input type="email" name="email" placeholder="Email" autocomplete="off" required>
                <input type="text" name="fullname" placeholder="Full Name" autocomplete="off" required>
                <input type="text" name="username" placeholder="Username" autocomplete="off" required>
                <input type="password" name="password" placeholder="Password" autocomplete="off" required>
            </div>
            <button id="signupBtn" type="submit">Sign up</button>
        </form>
    </div>
    <div class="auth-redir-msg">
        <p>Have an account? <a href="/accounts/login">Log in</a></p>
    </div>
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/accounts/auth.js" ?>"></script>
    <script src="<?= $baseUrl . "scripts/api.js" ?>"></script>
</div>
