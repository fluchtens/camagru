<?php
// require "./core/database.php";

if (!isset($_SESSION['id'])) {
    header("Location: /login");
    exit();
}

$db = connectToDatabase();
$id = $_SESSION['id'];

$query = "SELECT * FROM user WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<div class="main-container">
    <?php if (isset($_SESSION['id'])): ?>
        <!-- <div class="take-picture">
            <video id="captureCamera" autoplay></video>
            <button id="captureBtn">Take Photo</button>
            <script src="scripts/capture.js"></script>
        </div> -->
        <?php require "./views/partials/feed.php"?>
    <?php else: ?>
        <p>You are not logged in.</p>
    <?php endif; ?>
</div>

