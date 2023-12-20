<?php
require '../database.php';

session_start();
if (isset($_SESSION['id'])) {
    header("Location: /");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectToDatabase();   
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty";
    } else {
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                header("Location: /");
                exit();
            } else {
                $error = "Incorrect username or password";
            }
        } else {
            $error = "Incorrect username or password";
        }
    }
    $conn->close();
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
            <img src="../assets/camagru.png" alt="camagru.png">
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
