<?php
require '../database/database.php';
require '../user/user.service.php';

session_start();
if (isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}

function registerUser($username, $password) {
    try {
        if (empty($username) || empty($password)) {
            return ['code' => 401, 'message' => "Username and password cannot be empty."];
        }

        $db = connectToDatabase();  

        if (getUserByUsername($db, $username)) {
            return ['code' => 409, 'message' => "This username is already taken."];
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO user (username, password) VALUES (:username, :hashed_password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':hashed_password', $hashed_password);
        $stmt->execute();

        return ['code' => 200, 'message' => "User succesfully created."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $response = registerUser($username, $password);
    http_response_code($response['code']);
    if ($response['code'] == 200) {
        header("Location: login.php");
        exit();
    }
    $error = $response['message'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" type="text/css" href="auth.css">
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
            <p>Have an account? <a href="login.php">Log in</a></p>
        </div>
    </main>
</body>
</html>
