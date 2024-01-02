<?php
if (!isset($_SESSION['id'])) {
    header("Location: /login");
    exit();
}
?>

<div class="post-container">
    <h1>Create new post</h1>
    <div class="take-picture">
        <video id="captureCamera" autoplay></video>
        <button id="captureBtn">Take Photo</button>
        <script src="scripts/capture.js"></script>
    </div>
</div>