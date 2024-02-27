<?php
session_start();

require "../../core/database.php";
require "../../models/user.model.php";

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }

        $db = connectToDatabase();
        $userId = $_SESSION['id'];

        $user = getUserById($db, $userId);

        $fieldsToExclude = ['active', 'activation_token', 'reset_token', 'reset_token_expiration', 'password'];
        foreach ($fieldsToExclude as $field) {
            unset($user[$field]);
        }

        $decodedFields = ['full_name', 'bio'];
        foreach ($decodedFields as $field) {
            if (isset($user[$field])) {
                $user[$field] = html_entity_decode($user[$field]);
            }
        }

        return ['code' => 200, 'user' => $user];
    } catch (Exception $e) {
        return ['code' => 500, 'message' => "An error occurred: " . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $response = submitData();
    http_response_code($response['code']);
    header('Content-Type: application/json');
    if ($response['code'] !== 200) {
        echo json_encode($response);
    } else {
        echo json_encode($response['user']);
    }
    exit();
}
?>
