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
        <img class="captureFilter" id="captureFilter" alt="filter">
        <canvas id="photoPreview"></canvas>
        <img class="previewFilter" id="previewFilter" alt="filter">
        <div class="filters">
            <?php foreach($filters as $filter): ?>
                <button class="filterBtn" data-id="<?= $filter['id'] ?>" data-file="<?= $filter['file'] ?>">
                    <img src="<?= "assets/filters/" . $filter['file'] ?>" alt="<?= $filterName ?>">
                </button>
            <?php endforeach; ?>
        </div>
        <div class="buttons">
            <button class="takePhotoBtn" id="takePhotoBtn" disabled>Take Photo</button>
            <button class="cancelBtn" id="cancelBtn">Cancel</button>
            <button class="submitBtn" id="submitBtn">Submit</button>
        </div>
    </div>
    <script src="scripts/createPost.js"></script>
    <script src="scripts/selectFilter.js"></script>
</div>
