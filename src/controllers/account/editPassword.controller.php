<?php
session_start();

require "../../core/database.php";
require "../../models/user.model.php";
require "../../core/utils.php";

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $oldPassword = isset($_POST['old-password']) ? trim(htmlspecialchars($_POST['old-password'])) : null;
        $newPassword = isset($_POST['new-password']) ? trim(htmlspecialchars($_POST['new-password'])) : null;
        $confirmNewPassword = isset($_POST['confirm-new-password']) ? trim(htmlspecialchars($_POST['confirm-new-password'])) : null;

        if (!isset($oldPassword) || !isset($newPassword) || !isset($confirmNewPassword)) {
            return ['code' => 400, 'message' => "There are one or more required fields missing from the form."];
        }

        $newPasswordCheck = checkPassword($newPassword);
        if (!$newPasswordCheck['success']) {
            return ['code' => 400, 'message' => $newPasswordCheck['message']];
        }

        if ($confirmNewPassword !== $newPassword) {
            return ['code' => 400, 'message' => "Password confirmation doesn't match the password"];
        }

        $db = connectToDatabase();
        $userId = $_SESSION['id'];
        $user = getUserById($db, $userId);

        if (!password_verify($oldPassword, $user['password'])) {
            return ['code' => 401, 'message' => "Old password isn't valid"];
        }

        if (password_verify($newPassword, $user['password'])) {
            return ['code' => 400, 'message' => "The new password cannot be the same as the old password."];
        }

        updatePassword($db, $userId, $newPassword);
        return ['code' => 200, 'message' => "Password succesfully updated."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = submitData();
    http_response_code($response['code']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>