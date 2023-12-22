<?php
require '../database/database.php';

session_start();
if (isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}

function loginUser($username, $password) {
    try {
        if (empty($username) || empty($password)) {
            return ['code' => 401, 'message' => "Username and password cannot be empty."];
        }

        $db = connectToDatabase();  
        $query = "SELECT * FROM user WHERE username = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            return ['code' => 401, 'message' => "Incorrect username or password."];
        }
        
        $_SESSION['id'] = $user['id'];
        return ['code' => 200, 'message' => "User succesfully connected."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $response = loginUser($username, $password);
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
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
    </main>
</body>
</html>
