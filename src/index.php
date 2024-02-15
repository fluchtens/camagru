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

function shouldIncludeHeader($uri) {
    $exclude = ["/accounts/signup", "/accounts/login", "/accounts/password/forgot"];
    return !in_array($uri, $exclude);
}

function router($db, $uri, $uriArray, $baseUrl) {
    switch ($uri) {
        case "/":
            $content = "home.php";
            $css = "home.css";
            break;
        case "/accounts/signup":
            $content = "accounts/signup.php";
            $css = "accounts/auth.css";
            break;
        case "/accounts/login":
            $content = "accounts/login.php";
            $css = "accounts/auth.css";
            break;
        case "/logout":
            $content = "accounts/logout.php";
            break;
        case "/create":
            $content = "posts/create.php";
            $css = "posts/create.css";
            break;
        case "/accounts/edit":
            $content = "accounts/edit.php";
            $css = "accounts/edit.css";
            break;
        case "/accounts/password/forgot":
            $content = "accounts/password/forgotPassword.php";
            $css = "accounts/password/forgotPassword.css";
            break;
        default:
            if (count($uriArray) === 3 && $uriArray[1] === "p") {
                $post = getPostById($db, $uriArray[2]);
                $content = $post ? "posts/post.php" : "404.php";
                $css = $post ? "posts/post.css" : "404.css";
                break;
            }
            elseif (count($uriArray) === 3 && $uriArray[1] === "c") {
                $post = getPostById($db, $uriArray[2]);
                $content = $post ? "posts/comments.php" : "404.php";
                $css = $post ? "posts/comments.css" : "404.css";
                break;
            }
            elseif (count($uriArray) === 3 && $uriArray[1] === "accounts" && str_starts_with($uriArray[2], "verification?token=")) {
                $content = "accounts/verification.php";
                $css = "accounts/verification.css";
                break;
            }
            elseif (count($uriArray) === 4 && $uriArray[1] === "accounts" && $uriArray[2] === "password" && str_starts_with($uriArray[3], "reset?token=")) {
                $content = "accounts/password/resetPassword.php";
                $css = "accounts/password/resetPassword.css";
                break;
            }
            elseif (count($uriArray) > 2) {
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
$userId = $_SESSION['id'] ?? null;
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
        <link rel="stylesheet" type="text/css" href=<?= $baseUrl . "styles/partials/footer.css"; ?>>
        <?php if ($css): ?>
            <link rel="stylesheet" type="text/css" href="<?= $css; ?>">
        <?php endif; ?>  
        <title>camagru</title>
    </head>
    <body>
        <?php if (shouldIncludeHeader($uri)): ?>
            <?php require "./views/partials/header.php"; ?>
        <?php endif; ?>
        <main>
            <?php require $content; ?>
        </main>
        <?php if (shouldIncludeHeader($uri)): ?>
            <?php require "./views/partials/footer.php"; ?>
        <?php endif; ?>
    </body>
</html>

<?php
ob_end_flush();
?>
