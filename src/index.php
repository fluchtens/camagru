<?php
require "./core/database.php";
require "./core/isAuth.php";
require "./models/user.model.php";
require "./models/post.model.php";
require "./models/filter.model.php";

ob_start();
session_start();

$db = connectToDatabase();
$userId = $_SESSION['id'];
$uri = $_SERVER["REQUEST_URI"];
$uriArray = explode('/', rtrim($uri, '/'));
$requestScheme = $_SERVER['REQUEST_SCHEME'];
$httpHost = $_SERVER['HTTP_HOST'];
$appPath = $requestScheme . "://" . $httpHost . "/";

switch ($uri) {
    case "/":
        $content = "views/home.php";
        $css = "styles/feed.css";
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
        $content = "views/logout.php";
        break;
    case "/post":
        $content = "views/post.php";
        $css = "styles/post.css";
        break;
    case "/settings":
        $content = "views/settings.php";
        $css = "styles/settings.css";
        break;
    default:
        if (count($uriArray) > 2) {
            $content = "views/404.php";
            $css = $appPath . "styles/404.css";
            break;
        }
        $user = getUserByUsername($db, $uriArray[1]);
        if (!$user) {
            $content = "views/404.php";
            $css = $appPath . "styles/404.css";
        } else {
            $content = "views/profile.php";
            $css = $appPath . "styles/profile.css";
        }
        break;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" type="text/css" href=<?php echo $appPath . "styles/globals.css" ?>>
        <link rel="stylesheet" type="text/css" href=<?php echo $appPath . "styles/header.css" ?>>
        <?php
        if ($css) {
            echo '<link rel="stylesheet" type="text/css" href="' . $css . '">';
        }
        ?>
        <title>camagru</title>
    </head>
    <body>
        <?php
            if ($userId && $uri !== "/register" && $uri !== "/login") {
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
