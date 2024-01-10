<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";
require "../core/utils.php";

function submitData($username) {
    try {
        $id = $_SESSION['id'];
        $db = connectToDatabase();
    
        $usernameCheck = checkUsername($username);
        if (!$usernameCheck['success']) {
            return ['code' => 400, 'message' => $usernameCheck['message']];
        }
    
        if (getUserByUsername($db, $username)) {
            return ['code' => 409, 'message' => "This username is already taken."];
        }
    
        updateUsername($db, $id, $username);
        return ['code' => 200, 'message' => "Username succesfully updated."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = htmlspecialchars($_POST['newUsername']);
    $response = submitData($newUsername);
    http_response_code($response['code']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>