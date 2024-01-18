<?php
if (!isset($_SESSION['id'])) {
    header("Location: /login");
    exit();
}

$filters = getAllFilters();
?>

<div class="post">s
    <h1>Create new post</h1>
    <div class="take">
        <video id="captureVideo" autoplay></video>
        <img class="captureFilter" id="captureFilter" src="assets/filters/fire.png" alt="filter">
        <canvas id="photoPreview"></canvas>
        <img class="previewFilter" id="previewFilter" src="assets/filters/fire.png" alt="filter">
        <div class="filters">
            <?php foreach($filters as $filterName => $filterPath): ?>
                <button class="filterBtn" data-name="<?= $filterName ?>" data-path="<?= $filterPath ?>">
                    <img src="<?= "assets/filters/" . $filterPath ?>" alt="<?= $filterName ?>">
                </button>
            <?php endforeach; ?>
        </div>
        <div class="buttons">
            <button class="takePhotoBtn" id="takePhotoBtn">Take Photo</button>
            <button class="cancelBtn" id="cancelBtn">Cancel</button>
            <button class="submitBtn" id="submitBtn">Submit</button>
        </div>
    </div>
    <script src="scripts/capture.js"></script>
    <script src="scripts/selectFilter.js"></script>
</div>
