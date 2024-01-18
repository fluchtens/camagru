<?php
if (!isset($_SESSION['id'])) {
    header("Location: /login");
    exit();
}

$filters = getAllFilters($db);
?>

<div class="post">
    <h1>Create new post</h1>
    <div class="take">
        <video id="captureVideo" autoplay></video>
        <img id="captureFilter" class="captureFilter" alt="filter">
        <canvas id="photoPreview"></canvas>
        <img id="previewFilter" class="previewFilter" alt="filter">
        <div id="filters" class="filters">
            <?php foreach($filters as $filter): ?>
                <button class="filterBtn" data-id="<?= $filter['id'] ?>" data-file="<?= $filter['file'] ?>">
                    <img src="<?= "assets/filters/" . $filter['file'] ?>" alt="<?= $filter['file'] ?>">
                </button>
            <?php endforeach; ?>
        </div>
        <div class="buttons">
            <button id="takePhotoBtn" class="takePhotoBtn" disabled>Take Photo</button>
            <button id="cancelBtn" class="cancelBtn">Cancel</button>
            <button id="submitBtn" class="submitBtn">Submit</button>
        </div>
    </div>
    <script src="scripts/createPost.js"></script>
</div>
