<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";
require "../core/utils.php";
require "../core/sendEmail.php";

function checkBody($username, $email, $password) {
    if (!$username) {
        return ['code' => 401, 'message' => "Username cannot be empty."];
    } elseif (!$email) {
        return ['code' => 401, 'message' => "Email cannot be empty."];
    } elseif (!$password) {
        return ['code' => 401, 'message' => "Password cannot be empty."];
    } else {
        return ['code' => 200];
    }
}

function submitData() {
    try {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        $bodyCheck = checkBody($username, $email, $password);
        if ($bodyCheck['code'] !== 200) {
            return ['code' => $bodyCheck['code'], 'message' => $bodyCheck['message']];
        }

        // $usernameCheck = checkUsername($username);
        // if (!$usernameCheck['success']) {
        //     return ['code' => 400, 'message' => $usernameCheck['message']];
        // }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['code' => 400, 'message' => "Email is invalid."];
        }

        // $passwordCheck = checkPassword($password);
        // if (!$passwordCheck['success']) {
        //     return ['code' => 400, 'message' => $passwordCheck['message']];
        // }

        $db = connectToDatabase();  

        if (getUserByUsername($db, $username)) {
            return ['code' => 409, 'message' => "This username is already taken."];
        }

        createUser($db, $username, $email, $password);


        sendActivationEmail($email);

        return ['code' => 200, 'message' => "User succesfully created."];
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