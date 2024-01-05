<?php
require "./core/utils.php";

function registerUser($username, $password) {
    try {
        if (empty($username) || empty($password)) {
            return ['code' => 401, 'message' => "Username and password cannot be empty."];
        }

        // $usernameCheck = checkUsername($username);
        // if (!$usernameCheck['success']) {
        //     return ['code' => 400, 'message' => $usernameCheck['message']];
        // }

        // $passwordCheck = checkPassword($password);
        // if (!$passwordCheck['success']) {
        //     return ['code' => 400, 'message' => $passwordCheck['message']];
        // }

        $db = connectToDatabase();  

        if (getUserByUsername($db, $username)) {
            return ['code' => 409, 'message' => "This username is already taken."];
        }

        addUser($db, $username, $password);
        return ['code' => 200, 'message' => "User succesfully created."];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $response = registerUser($username, $password);
    http_response_code($response['code']);
    if ($response['code'] === 200) {
        header("Location: /login");
        exit();
    }
    $error = $response['message'];
}
?>