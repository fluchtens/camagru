<?php
if (!isAuth()) {
    header("Location: /accounts/login");
    exit();
}

$filters = getAllFilters($db);
?>

<div class="post">
    <h1>Create new post</h1>
    <div id="msg" class="msg">
        <p id="msgText">ds</p>
    </div>
    <div class="take">
        <div class="capture">
            <video id="captureVideo" autoplay></video>
            <img id="captureFilter" alt="filter">
        </div>
        <div class="preview">
            <canvas id="photoPreview"></canvas>
            <img id="importPreview" class="import" alt="import_preview">
            <img id="previewFilter" class="filter" alt="filter">
        </div>
        <div id="filters" class="filters">
            <?php foreach($filters as $filter): ?>
                <button class="filterBtn" data-id="<?= $filter['id'] ?>" data-file="<?= $filter['file'] ?>">
                    <img src="<?= "assets/filters/" . $filter['file'] ?>" alt="<?= $filter['file'] ?>">
                </button>
            <?php endforeach; ?>
        </div>
        <div id="waiting" class="waiting"></div>
        <div class="buttons">
            <button id="takePhotoBtn" class="take-photo-btn" disabled>Take Photo</button>
            <div class="file-input">
                <input id="importInput" type="file" accept=".jpg, .jpeg, .png, .gif">
                <label id="importBtn" for="importInput">Import Photo</label>
            </div>
            <button id="publishPhotoBtn" class="publish-photo-btn">Publish Photos</button>
            <button id="cancelBtn" class="cancel-btn">Cancel</button>
            <button id="saveBtn" class="save-btn">Save</button>
        </div>
    </div>
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/posts/create.js" ?>"></script>
    <script src="<?= $baseUrl . "scripts/api.js" ?>"></script>
</div>
