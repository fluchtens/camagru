<?php
if (!isAuth()) {
    header("Location: /accounts/login");
    exit();
}

$filters = getAllFilters($db);
$waitingPosts = getUserWaitingPosts($db, $userId);
?>

<div class="post">
    <h1>Create new post</h1>
    <div class="take">
        <div class="capture">
            <video id="captureVideo" autoplay></video>
            <img id="captureFilter" alt="filter">
        </div>
        <div class="preview">
            <canvas id="photoPreview"></canvas>
            <img id="previewFilter" alt="filter">
        </div>
        <div id="filters" class="filters">
            <?php foreach($filters as $filter): ?>
                <button class="filterBtn" data-id="<?= $filter['id'] ?>" data-file="<?= $filter['file'] ?>">
                    <img src="<?= "assets/filters/" . $filter['file'] ?>" alt="<?= $filter['file'] ?>">
                </button>
            <?php endforeach; ?>
        </div>
        <?php if ($waitingPosts): ?>
            <div id="waiting" class="waiting">
                <?php foreach($waitingPosts as $waitingPost): ?>
                    <img src="<?= $baseUrl . "assets/uploads/posts/" . $waitingPost['file'] ?>" alt="<?= $waitingPost['file'] ?>">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="buttons">
            <button id="takePhotoBtn" class="take-photo-btn" disabled>Take Photo</button>
            <div class="file-input">
                <input id="importInput" type="file" accept=".jpg, .jpeg, .png">
                <label id="importBtn" for="importInput">Import Photo</label>
            </div>
            <?php if (isset($waitingPost)): ?>
                <button id="publishPhotoBtn" class="publish-photo-btn">Publish Photos</button>
            <?php else: ?>
                <button id="publishPhotoBtn" class="publish-photo-btn" disabled>Publish Photos</button>
            <?php endif; ?>
            <button id="cancelBtn" class="cancel-btn">Cancel</button>
            <button id="saveBtn" class="save-btn">Save</button>
        </div>
    </div>
    <script>const baseUrl = "<?= $baseUrl ?>";</script>
    <script src="<?= $baseUrl . "scripts/create.js" ?>"></script>
</div>
