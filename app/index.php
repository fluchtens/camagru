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
        <title>camagru</title>
        <link rel="stylesheet" type="text/css" href="globals.css">
        <link rel="stylesheet" type="text/css" href="layouts/header.css">
    </head>
<body>
    <?php require 'layouts/header.php'; ?>
    <main>
        <div class="container">
            <h1>Hi, <?php echo $username; ?>!</h1>
        </div>
    </main>
</body>
</html>
