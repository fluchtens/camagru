<?php
function isAuth() {
    try {
        if (!isset($_SESSION['id'])) {
            return (false);
        }
        $db = connectToDatabase();
        $userId = $_SESSION['id'];
        $user = getUserById($db, $userId);
        if (!$user || !$user['active']) {
            return (false);
        }
        return (true);
    } catch (Exception $e) {
        return (false);
    }
}
?>
