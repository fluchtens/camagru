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
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                header("Location: /");
                exit();
            } else {
                echo "Incorrect username or password";
            }
        } else {
            echo "Incorrect username or password";
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
            <button type="submit">Submit</button>
        </form>
        <p>Don't have an account yet? <a href="register.php">Sign up</a></p>
    </main>
</body>
</html>
