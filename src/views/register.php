<?php
require "./controllers/register.controller.php";

if (isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" type="text/css" href="../styles/globals.css">
        <link rel="stylesheet" type="text/css" href="../styles/auth.css">
        <title>camagru</title>
    </head>
<body>
    <main>
        <?php if ($error) echo '<div class="err-msg"><p>', $error, '</p></div>'; ?>
        <div class="container">
            <a class="main-link" href="/">
                <img src="../assets/camagru.png" alt="camagru.png">
            </a>
            <form method="POST" action="">
                <div class="input-container">
                    <input type="text" name="username" placeholder="Username" autocomplete="off" >
                    <input type="password" name="password" placeholder="Password" autocomplete="off" >
                </div>
                <button type="submit">Sign up</button>
            </form>
        </div>
        <div class="redir-msg">
            <p>Have an account? <a href="/login">Log in</a></p>
        </div>
    </main>
</body>
</html>
