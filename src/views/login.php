<?php
require "./controllers/login.controller.php";

if (isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}
?>

<?php if ($error): ?>
    <div class="auth-err-msg">
        <p><?php echo $error; ?></p>
    </div>
<?php endif; ?>
<div class="auth-form">
    <a class="main-link" href="/">
        <img src="assets/camagru.png" alt="camagru.png">
    </a>
    <form method="POST" action="">
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
