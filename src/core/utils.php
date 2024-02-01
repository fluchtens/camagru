<?php
function checkFullName($fullname) {
    if (strlen($fullname) < 1 || strlen($fullname) > 30) {
        return ['success' => false, 'message' => "Full name must be between 3 and 16 characters long."];
    }
    return ['success' => true, 'message' => null];
}

function checkUsername($username) {
    if (strlen($username) < 3 || strlen($username) > 16) {
        return ['success' => false, 'message' => "Username must be between 3 and 16 characters long."];
    }
    if (!ctype_alpha($username[0])) {
        return ['success' => false, 'message' => "Username must start with a letter."];
    }
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        return ['success' => false, 'message' => "Username can only contain letters, digits, underscores & hyphens."];
    }
    return ['success' => true, 'message' => null];
}

function checkPassword($password) {
    if (strlen($password) < 8 || strlen($password) > 30) {
        return ['success' => false, 'message' => "Password must be between 8 and 30 characters long."];
    }
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $password)) {
        return ['success' => false, 'message' => "Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character."];
    }
    return ['success' => true, 'message' => null];
}

function formatElapsedTime($timeDiff) {
    $timeComponents = explode(':', $timeDiff);
    $hours = $timeComponents[0];
    $minutes = $timeComponents[1];
    $seconds = $timeComponents[2];    
    $weeks = floor($hours / 24 / 7);
    $days = floor($hours / 24) % 7;

    if ($weeks > 0) {
        return $weeks . 'w';
    } elseif ($days > 0) {
        return $days . 'd';
    } elseif ($hours > 0) {
        return $hours . 'h';
    } elseif ($minutes > 0) {
        return $minutes . 'min';
    } elseif ($seconds > 0) {
        return $seconds . 's';
    } else {
        return 'Now';
    }
}
?>