<?php
require 'database.php';

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
                header("Location: index.php");
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
        <link rel="stylesheet" type="text/css" href="register.css">
    </head>
<body>
    <main>
        <h1>Login to your account</h1>
        <form method="POST" action="">
            <input type="text" name="username" autocomplete="off" placeholder="Enter your username" required>
            <input type="password" name="password" autocomplete="off" placeholder="Enter your password" required>
            <button type="submit">Submit</button>
        </form>
    </main>
</body>
</html>
