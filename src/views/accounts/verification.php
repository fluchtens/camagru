<?php
if (isAuth()) {
    header("Location: /");
    exit();
}

require "./controllers/account/verification.controller.php";
?>

<div class="verification">
    <p><?= $activationMessage; ?></p>
</div>
