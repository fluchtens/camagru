<?php
require "./core/database.php";
require "./core/isAuth.php";
require "./core/utils.php";
require "./models/user.model.php";
require "./models/post.model.php";
require "./models/comment.model.php";
require "./models/filter.model.php";

ob_start();
session_start();

function getBaseUrl() {
    $requestScheme = $_SERVER['REQUEST_SCHEME'];
    $httpHost = $_SERVER['HTTP_HOST'];
    $baseUrl = $requestScheme . "://" . $httpHost . "/";
    return ($baseUrl);
}

function router($db, $uri, $uriArray, $baseUrl) {
    switch ($uri) {
        case "/":
            $content = "home.php";
            $css = "home.css";
            break;
        case "/register":
            $content = "register.php";
            $css = "auth.css";
            break;
        case "/login":
            $content = "login.php";
            $css = "auth.css";
            break;
        case "/logout":
            $content = "logout.php";
            break;
        case "/create":
            $content = "create.php";
            $css = "create.css";
            break;
        case "/settings":
            $content = "settings.php";
            $css = "settings.css";
            break;
        default:
            if (count($uriArray) === 3 && $uriArray[1] === "p") {
                $post = getPostById($db, $uriArray[2]);
                $content = $post ? "post.php" : "404.php";
                $css = $post ? "post.css" : "404.css";
                break;
            }
            if (count($uriArray) === 3 && $uriArray[1] === "c") {
                $post = getPostById($db, $uriArray[2]);
                $content = $post ? "comments.php" : "404.php";
                $css = $post ? "comments.css" : "404.css";
                break;
            }
            if (count($uriArray) > 2) {
                $content = "404.php";
                $css = "404.css";
                break;
            }
            $user = getUserByUsername($db, $uriArray[1]);
            $content = $user ? "profile.php" : "404.php";
            $css = $user ? "profile.css" : "404.css";
            break;
    }
    $viewUrl = "views/" . $content;
    $cssUrl = $baseUrl . "styles/" . $css;
    return ['content' => $viewUrl, 'css' => $cssUrl];
}

$db = connectToDatabase();
$userId = $_SESSION['id'];
$uri = $_SERVER["REQUEST_URI"];
$uriArray = explode('/', rtrim($uri, '/'));
$baseUrl = getBaseUrl();
$router = router($db, $uri, $uriArray, $baseUrl);
$content = $router['content'];
$css = $router['css'];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="icon" type="image/png" href="<?= $baseUrl . "assets/favicon.png" ?>" />
        <link rel="stylesheet" type="text/css" href=<?= $baseUrl . "styles/globals.css"; ?>>
        <link rel="stylesheet" type="text/css" href=<?= $baseUrl . "styles/partials/header.css"; ?>>
        <?php if ($css): ?>
            <link rel="stylesheet" type="text/css" href="<?= $css; ?>">
        <?php endif; ?>  
        <title>camagru</title>
    </head>
    <body>
        <?php if ($uri !== "/register" && $uri !== "/login"): ?>
            <?php require "./views/partials/header.php"; ?>
        <?php endif; ?>
        <main>
            <?php require $content; ?>
        </main>
    </body>
</html>

<?php
ob_end_flush();
?>
