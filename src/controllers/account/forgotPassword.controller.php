<?php
session_start();

require "../../core/database.php";
require "../../models/user.model.php";
require "../../core/sendEmail.php";

function sendPasswordResetEmail($email, $resetToken) {
    $mailSubject = "Password Reset Request";
    $mailBody = "
        <div style='max-width: 640px; margin: 0 auto; text-align: center;'>
            <p>We received a request to reset the password for your Camagru account.</p>
            <p>If you did not make this request, you can ignore this email.</p>
            <p>Click the link below to reset your password:</p>
            <a href='http://localhost/accounts/password/reset?token=$resetToken'><strong>Reset Password</strong></a>
            <p>Please note that this link will expire in 15 min.</p>
            <p>Make sure to reset your password within this time frame.</p>
        </div>
    ";
    sendEmail($email, $mailSubject, $mailBody);
}

function submitData() {
    try {
        if (isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are already logged in."];    
        }

        $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : null;
        if (!isset($email)) {
            return ['code' => 400, 'message' => "Email cannot be empty."];
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['code' => 400, 'message' => "Email is invalid."];
        }

        $db = connectToDatabase();
        $user = getUserByEmail($db, $email);
        if (!$user) {
            return ['code' => 400, 'message' => "No account is linked to this email address."];
        }

        if ($user) {
            if (isset($user['reset_token_expiration'])) {
                if (strtotime($user['reset_token_expiration']) > time()) {
                    return ['code' => 400, 'message' => "You already have a password reset request in progress, please check your emails."];
                }
            }
        } else {
            return ['code' => 400, 'message' => "No account is linked to this email address."];
        }

        $resetToken = bin2hex(random_bytes(32));
        $expirationTime = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        updateResetToken($db, $user['id'], $resetToken, $expirationTime);
        $updatedUser = getUserById($db, $user['id']);
        sendPasswordResetEmail($updatedUser['email'], $updatedUser['reset_token']);
        return ['code' => 200, 'message' => "A password reset email has been successfully sent you."];
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
