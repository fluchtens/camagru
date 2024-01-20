<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";
require "../core/utils.php";
require "../core/sendEmail.php";

function submitData() {
    try {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        if (!$username || !$email || !$password) {
            return ['code' => 400, 'message' => "There are one or more required fields missing from the form."];
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

        $activationToken = bin2hex(random_bytes(16));
        createUser($db, $username, $email, $password, $activationToken);
        $mailSubject = "Confirmation of account registration";
        $mailBody = "
            <div style='max-width: 640px; margin: 0 auto; text-align: center;'>
                <img src='cid:logo' alt='logo' style='width: 300px'>
                <p>Thank you for creating a new account to access Camagru. To benefit from all Camagru services, you must verify the e-mail address on your account.</p>
                <a href='http://localhost:8080/controllers/accountVerification.controller.php?token=$activationToken'>Verify now</a>
            </div>
        ";
        sendEmail($email, $mailSubject, $mailBody);
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
