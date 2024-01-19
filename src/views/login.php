<?php
if (isset($_SESSION['id'])) {
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
            <img src="assets/camagru.png" alt="camagru.png">
        </a>
        <form id="loginForm">
            <div class="input-container">
                <input type="text" name="username" placeholder="Username" autocomplete="off" >
                <input type="password" name="password" placeholder="Password" autocomplete="off" >
            </div>
            <button type="submit">Sign up</button>
        </form>
    </div>
    <div class="auth-redir-msg">
        <p>Don't have an account? <a href="/register">Sign up</a></p>
    </div>
    <script src="scripts/loginUser.js"></script>
</div>
