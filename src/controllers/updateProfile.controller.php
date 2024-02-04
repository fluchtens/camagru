<?php
session_start();

require "../core/database.php";
require "../models/user.model.php";
require "../core/utils.php";

function submitFullName($db, $userId, $fullname) {
    if (strlen($fullname) < 1 || strlen($fullname) > 30) {
        return ['code' => 400, 'message' => "Full name must be between 3 and 16 characters long."];
    }

    updateFullName($db, $userId, $fullname);
    return ['code' => 200, 'message' => null];
}

function submitUsername($db, $userId, $username) {
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

function submitBio($db, $userId, $bio) {
    if (strlen($bio) < 0 || strlen($bio) > 150) {
        return ['code' => 400, 'message' => "Bio must be between 1 and 150 characters long."];
    }

    updateBio($db, $userId, $bio);
    return ['code' => 200, 'message' => null];
}

function submitData() {
    try {
        if (!isset($_SESSION['id'])) {
            return ['code' => 401, 'message' => "You are not logged in."];    
        }
        
        $fullname = trim(htmlspecialchars($_POST['fullname']));
        $username = trim(htmlspecialchars($_POST['username']));
        $bio = trim(htmlspecialchars($_POST['bio']));

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
            $submitUsername = submitUsername($db, $userId, $username);
            if ($submitUsername['code'] !== 200) {
                return ['code' => $submitUsername['code'], 'message' => $submitUsername['message']];
            }
        }

        if ($user['bio'] !== $bio) {
            $submitBio = submitBio($db, $userId, $bio);
            if ($submitBio['code'] !== 200) {
                return ['code' => $submitBio['code'], 'message' => $submitBio['message']];
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