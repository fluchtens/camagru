<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";

function submitData() {
    try {
        $username = trim(htmlspecialchars($_POST['username']));
        $password = trim(htmlspecialchars($_POST['password']));

        if (empty($username) || empty($password)) {
            return ['code' => 401, 'message' => "Username and password cannot be empty."];
        }

        $db = connectToDatabase();  
        $user = getUserByUsername($db, $username);
        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
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

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $response = submitData();
    http_response_code($response['code']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
