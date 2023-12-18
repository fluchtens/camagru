<?php
require 'database.php';

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
            echo "User registered successfully";
            header("Location: login.php");
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
        <link rel="stylesheet" type="text/css" href="register.css">
    </head>
<body>
    <main>
        <h1>Register a new account</h1>
        <form method="POST" action="">
            <input type="text" name="username" autocomplete="off" placeholder="Enter your username" required>
            <input type="text" name="password" autocomplete="off" placeholder="Enter your password" required>
            <button type="submit">Register</button>
        </form>
    </main>
</body>
</html>
