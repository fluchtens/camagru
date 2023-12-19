<?php
    require 'database.php';

    session_start();
    if (!isset($_SESSION['id'])) {
        header("Location: auth/login.php");
        exit();
    } else {
        $conn = connectToDatabase();
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM user WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $username = htmlspecialchars($row['username']);
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>camagru</title>
        <link rel="stylesheet" type="text/css" href="index.css">
    </head>
<body>
    <header>
        <div class="container">
            <a class="main-link" href="/">camagru</a>
            <a class="login-btn" href="auth/login.php">
                <button><?php echo $username; ?></button>
            </a>
        </div>
    </header>
    <main>
        <div class="container">
            <h1>Camagru</h1>
            <h2>Hi, <?php echo $username; ?>!</h2>
        </div>
    </main>
</body>
</html>
