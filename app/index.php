<?php
    session_start();
    if (!isset($_SESSION['id'])) {
        header("Location: auth/login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>camagru</title>
        <link rel="stylesheet" type="text/css" href="index.css">
    </head>
<body>
    <header>
        <div class="container">
            <a class="main-link" href="/">camagru</a>
            <a class="login-btn" href="auth/login.php">
                <button>Log in</button>
            </a>
        </div>
    </header>
    <main>
        <div class="container">
            <h1>Camagru</h1>
            <h2>Hi, </h2>
        </div>
    </main>
</body>
</html>
