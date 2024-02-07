<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";

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
        if (!isset($user)) {
            return ['code' => 400, 'message' => "No account is linked to this email address."];
        }

        $resetToken = bin2hex(random_bytes(32));
        $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

        updateResetToken($db, $user['id'], $resetToken, $expirationTime);
        return ['code' => 200, 'message' => "A password reset email has been successfully sent you."];
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
