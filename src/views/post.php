<?php
if (!isset($_SESSION['id'])) {
    header("Location: /login");
    exit();
}
?>

<div class="post">
    <h1>Create new post</h1>
    <div class="take">
        <video id="captureVideo" autoplay></video>
        <button id="takePhotoBtn">Take Photo</button>
    </div>
    <div class="preview">
        <canvas id="photoPreview"></canvas>
        <div class="buttons">
            <button class="cancelBtn" id="cancelBtn">Cancel</button>
            <button class="submitBtn" id="submitBtn">Submit</button>
        </div>
    </div>
    <script src="scripts/capture.js"></script>
</div>
