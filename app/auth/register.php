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
        echo "Username and password cannot be empty";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, password) VALUES ('$username', '$hashed_password')";
    
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>camagru</title>
        <link rel="stylesheet" type="text/css" href="auth.css">
    </head>
<body>
    <main>
        <img src="../assets/camagru.png" alt="camagru.png">
        <form method="POST" action="">
            <div class="input-container">
                <input type="text" name="username" placeholder="Username" autocomplete="off" required>
                <input type="password" name="password" placeholder="Password" autocomplete="off" required>
            </div>
            <button type="submit">Sign up</button>
        </form>
        <p>Have an account? <a href="login.php">Sign in</a></p>
    </main>
</body>
</html>
