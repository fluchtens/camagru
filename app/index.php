<?php
    require 'database.php';

    session_start();
    if (isset($_SESSION['id'])) {
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" type="text/css" href="globals.css">
        <link rel="stylesheet" type="text/css" href="layouts/header.css">
        <title>camagru</title>
    </head>
<body>
    <?php require 'layouts/header.php'; ?>
    <main>
        <div class="container">
            <?php if (isset($_SESSION['id'])): ?>
                <h1>Hi, <?php echo $username; ?>!</h1>
            <?php else: ?>
                <p>You are not logged in.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
