<?php
ob_start();
session_start();
$path = $_SERVER["REQUEST_URI"];
switch ($path) {
    case "/":
        $content = "./views/home.php";
        break;
    case "/register":
        $content = "views/register.php";
        $css = "styles/auth.css";
        break;
    case "/login":
        $content = "views/login.php";
        $css = "styles/auth.css";
        break;
    case "/logout":
        $content = "./views/logout.php";
        break;
    case "/profile":
        $content = "./views/profile.php";
        break;
    default:
        $content = "views/404.php";
        $css = "styles/404.css";
        break;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" type="text/css" href="styles/globals.css">
        <link rel="stylesheet" type="text/css" href="styles/header.css">
        <?php
        if ($css) {
            echo '<link rel="stylesheet" type="text/css" href="' . $css . '">';
        }
        ?>
        <title>camagru</title>
    </head>
    <body>
        <?php
            if ($path !== "/register" && $path !== "/login") {
                require "./views/partials/header.php";
            }
        ?>
        <main>
            <?php require $content; ?>
        </main>
    </body>
</html>

<?php
ob_end_flush();
?>
