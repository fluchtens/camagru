<?php
try {
    $activationToken = $_GET['token'] ?? null;
    if (!$activationToken) {
        $activationMessage = "Missing token.";
        return;
    }

    $user = getUserByActivationToken($db, $activationToken);
    if (!$user) {
        $activationMessage = "This link is invalid. Please contact the sender of the email for more information.";
        return;
    }

    if ($user['active']) {
        $activationMessage = "This link has expired. Please contact the sender of the email for more information.";
    } else {
        updateActiveStatus($db, $user['id'], 1);
        $activationMessage = "Your account has been successfully activated!";
    }
} catch (Exception $e) {
    $activationMessage = $e->getMessage();
}
?>
