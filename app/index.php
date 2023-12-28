<?php
    require 'database/database.php';

    session_start();
    if (isset($_SESSION['id'])) {
        $db = connectToDatabase();
        $id = $_SESSION['id'];

        $query = "SELECT * FROM user WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" type="text/css" href="styles/globals.css">
        <link rel="stylesheet" type="text/css" href="styles/header.css">
        <link rel="stylesheet" type="text/css" href="styles/feed.css">
        <title>camagru</title>
    </head>
    <body>
        <?php require 'layouts/header.php'; ?>
        <main>
            <div class="main-container">
                <?php if (isset($_SESSION['id'])): ?>
                    <div class="take-picture">
                        <video id="captureCamera" autoplay></video>
                        <button id="captureBtn">Take Photo</button>
                        <script src="posts/capture.js"></script>
                    </div>
                    <?php require 'posts/feed.php'?>
                <?php else: ?>
                    <p>You are not logged in.</p>
                <?php endif; ?>
            </div>
        </main>
    </body>
</html>
