<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";
require "../core/utils.php";

function submitFullName($db, $userId, $fullname) {
    $fullnameCheck = checkFullName($fullname);
    if (!$fullnameCheck['success']) {
        return ['code' => 400, 'message' => $fullnameCheck['message']];
    }

    updateFullName($db, $userId, $fullname);
    return ['code' => 200, 'message' => null];
}

function sumbitUsername($db, $userId, $username) {
    $usernameCheck = checkUsername($username);
    if (!$usernameCheck['success']) {
        return ['code' => 400, 'message' => $usernameCheck['message']];
    }

    if (getUserByUsername($db, $username)) {
        return ['code' => 409, 'message' => "This username is already taken."];
    }

    updateUsername($db, $userId, $username);
    return ['code' => 200, 'message' => null];
}

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }
        
        $fullname = trim(htmlspecialchars($_POST['fullname']));
        $username = trim(htmlspecialchars($_POST['username']));

        $userId = $_SESSION['id'];
        $db = connectToDatabase();

        $user = getUserById($db, $userId);

        if ($user['full_name'] !== $fullname) {
            $submitFullName = submitFullName($db, $userId, $fullname);
            if ($submitFullName['code'] !== 200) {
                return ['code' => $submitFullName['code'], 'message' => $submitFullName['message']];
            }
        }

        if ($user['username'] !== $username) {
            $sumbitUsername = sumbitUsername($db, $userId, $username);
            if ($sumbitUsername['code'] !== 200) {
                return ['code' => $sumbitUsername['code'], 'message' => $sumbitUsername['message']];
            }
        }
    
        return ['code' => 200, 'message' => "Profile succesfully updated."];
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