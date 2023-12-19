<?php
    session_start();
    if (!isset($_SESSION['id'])) {
        header("Location: auth/login.php");
        exit();
    }
    echo $_SESSION['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>camagru</title>
</head>
<body>
    <h1>Camagru</h1>
    <h2>Hi, </h2>
</body>
</html>
