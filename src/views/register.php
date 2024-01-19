<?php
if (isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}
?>

<div class="auth">
    <div id="registerErrMsg" class="err-msg">
        <p id="registerErrMsgText"></p>
    </div>
    <div class="auth-form">
        <a class="main-link" href="/">
            <img src="assets/camagru.png" alt="camagru.png">
        </a>
        <form id="registerForm">
            <div class="input-container">
                <input type="text" name="username" placeholder="Username" autocomplete="off" >
                <input type="email" name="email" placeholder="Email" autocomplete="off" >
                <input type="password" name="password" placeholder="Password" autocomplete="off" >
            </div>
            <button type="submit">Sign up</button>
        </form>
    </div>
    <div class="auth-redir-msg">
        <p>Have an account? <a href="/login">Log in</a></p>
    </div>
    <script src="scripts/registerUser.js"></script>
</div>
