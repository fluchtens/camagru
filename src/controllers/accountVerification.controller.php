<?php
require "../core/database.php";
require "../models/user.model.php";

try {
    $db = connectToDatabase();
    $activationToken = $_GET['token'];

    $user = getUserByActivationToken($db, $activationToken);
    if (!$user) {
        echo 'This link is invalid. Please contact the sender of the email for more information.';
        return;
    }

    if ($user['active']) {
        echo 'This link has expired. Please contact the sender of the email for more information.';
    } else {
        updateActiveStatus($db, $user['id'], 1);
        echo 'Your account has been successfully activated!';
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
