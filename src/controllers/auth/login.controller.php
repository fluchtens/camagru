<?php
session_start();

require "../../core/database.php";
require "../../models/user.model.php";

function submitData() {
    try {
        $username = isset($_POST['username']) ? trim(htmlspecialchars($_POST['username'])) : null;
        $password = isset($_POST['password']) ? trim(htmlspecialchars($_POST['password'])) : null;

        if (!$username || !$password) {
            return ['code' => 401, 'message' => "Username and password cannot be empty."];
        }

        $db = connectToDatabase();  
        $user = getUserByUsername($db, $username);
        if (!$user || !password_verify($password, $user['password'])) {
            return ['code' => 401, 'message' => "Incorrect username or password."];
        }

        if (!$user['active']) {
            return ['code' => 401, 'message' => "Please verify your account by clicking on the confirmation link we sent to your email address."];
        }

        $_SESSION['id'] = $user['id'];
        return ['code' => 200, 'message' => "User succesfully connected."];
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
