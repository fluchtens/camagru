<?php
require '../database.php';

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
        <h1>Register a new account</h1>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Enter your username" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Submit</button>
            <p>Have an account? <a href="login.php">Sign in</a></p>
        </form>
    </main>
</body>
</html>
