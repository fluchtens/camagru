<?php
session_start();

require "../../core/database.php";
require "../../models/user.model.php";
require "../../core/utils.php";
require "../../core/sendEmail.php";

function sendVerificationEmail($email, $activationToken) {
    $mailSubject = "Confirmation of account registration";
    $mailBody = "
        <div style='max-width: 640px; margin: 0 auto; text-align: center;'>
            <img src='cid:logo' alt='logo' style='width: 300px'>
            <p>Thank you for creating a new account to access Camagru. To benefit from all Camagru services, you must verify the e-mail address on your account.</p>
            <a href='http://localhost/accounts/verification?token=$activationToken'>Verify now</a>
        </div>
    ";
    sendEmail($email, $mailSubject, $mailBody);
}

function submitData() {
    try {
        $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : null;

        if (isset($_POST['fullname'])) {
            $fullname = trim(htmlspecialchars($_POST['fullname']));
            $fullname = preg_replace('/\s+/', ' ', $fullname);
            $fullname = html_entity_decode($fullname);
        } else {
            $fullname = null;
        }

        $username = isset($_POST['username']) ? trim(htmlspecialchars($_POST['username'])) : null;
        $password = isset($_POST['password']) ? trim(htmlspecialchars($_POST['password'])) : null;

        if (!$email || !$fullname || !$username || !$password) {
            return ['code' => 400, 'message' => "There are one or more required fields missing from the form."];
        }

        $fullnameCheck = checkFullName($fullname);
        if (!$fullnameCheck['success']) {
            return ['code' => 400, 'message' => $fullnameCheck['message']];
        }

        $usernameCheck = checkUsername($username);
        if (!$usernameCheck['success']) {
            return ['code' => 400, 'message' => $usernameCheck['message']];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['code' => 400, 'message' => "Email is invalid."];
        }

        $passwordCheck = checkPassword($password);
        if (!$passwordCheck['success']) {
            return ['code' => 400, 'message' => $passwordCheck['message']];
        }

        $db = connectToDatabase();  

        if (getUserByUsername($db, $username)) {
            return ['code' => 409, 'message' => "This username is already taken."];
        }

        if (getUserByEmail($db, $email)) {
            return ['code' => 409, 'message' => "This e-mail is already in use by another user."];
        }

        $activationToken = bin2hex(random_bytes(16));
        createUser($db, $username, $email, $fullname, $password, $activationToken);
        sendVerificationEmail($email, $activationToken);
        return ['code' => 200, 'message' => "User succesfully created. To benefit from all Camagru services, please verify your account by clicking on the confirmation link we sent to your email address."];
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
