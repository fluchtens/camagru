<?php
require "./core/utils.php";

function submitData($username) {
    try {
        $db = connectToDatabase();
        $id = $_SESSION['id'];
    
        $usernameCheck = checkUsername($username);
        if (!$usernameCheck['success']) {
            return ['code' => 400, 'message' => $usernameCheck['message']];
        }
    
        if (getUserByUsername($db, $username)) {
            return ['code' => 409, 'message' => "This username is already taken."];
        }
    
        updateUsername($db, $id, $username);
        return ['code' => 200, 'message' => "User succesfully created."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = htmlspecialchars($_POST['username']);
    $response = submitData($newUsername);
    http_response_code($response['code']);
    if ($response['code'] !== 200) {
        $error = $response['message'];
    }
}
?>