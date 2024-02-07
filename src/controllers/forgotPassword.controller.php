<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";

function submitData() {
    try {
        $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : null;

        if (!$email) {
            return ['code' => 400, 'message' => "Email cannot be empty."];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['code' => 400, 'message' => "Email is invalid."];
        }

        $resetToken = bin2hex(random_bytes(32));
        $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

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
