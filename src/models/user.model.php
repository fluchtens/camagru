<?php
/* ************************************************************************** */
/*                                   SELECT                                   */
/* ************************************************************************** */

function getUserById($db, $userId) {
    $query = "SELECT *
            FROM user
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($user ? $user : null);
}

function getUserByUsername($db, $username) {
    $query = "SELECT *
            FROM user
            WHERE username = :username
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($user ? $user : null);
}

function getUserByEmail($db, $email) {
    $query = "SELECT *
            FROM user
            WHERE email = :email
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($user ? $user : null);
}

function getUserByActivationToken($db, $activationToken) {
    $query = "SELECT *
            FROM user
            WHERE activation_token = :activationToken
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':activationToken', $activationToken, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($user ? $user : null);
}

function getUserByResetToken($db, $resetToken) {
    $query = "SELECT *
            FROM user
            WHERE reset_token = :resetToken
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':resetToken', $resetToken, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($user ? $user : null);
}

/* ************************************************************************** */
/*                                   UPDATE                                   */
/* ************************************************************************** */

function updateUsername($db, $userId, $newUsername) {
    $query = "UPDATE user
            SET username = :newUsername
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

function updateEmail($db, $userId, $newEmail) {
    $query = "UPDATE user
            SET email = :newEmail
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':newEmail', $newEmail, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

function updateFullName($db, $userId, $fullName) {
    $query = "UPDATE user
            SET full_name = :fullName
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':fullName', $fullName, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

function updateBio($db, $userId, $bio) {
    $query = "UPDATE user
            SET bio = :bio
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    if (!$bio) {
        $stmt->bindValue(':bio', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);
    }
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

function updateAvatar($db, $userId, $avatar) {
    $query = "UPDATE user
            SET avatar = :avatar
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':avatar', $avatar, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

function updateEmailNotifs($db, $userId, $notifications) {
    $query = "UPDATE user
            SET email_notifs = :notifications
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':notifications', $notifications, PDO::PARAM_BOOL);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

function updateActiveStatus($db, $userId, $active) {
    $query = "UPDATE user
            SET active = :active
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

function updateResetToken($db, $userId, $resetToken, $expirationTime) {
    $query = "UPDATE user
            SET reset_token = :resetToken, reset_token_expiration = :expirationTime
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':resetToken', $resetToken, PDO::PARAM_STR);
    $stmt->bindParam(':expirationTime', $expirationTime, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

function updatePassword($db, $userId, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE user
            SET password = :hashed_password, reset_token = NULL, reset_token_expiration = NULL
            WHERE id = :userId
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':hashed_password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

/* ************************************************************************** */
/*                                INSERT INTO                                 */
/* ************************************************************************** */

function createUser($db, $username, $email, $fullname, $password, $activationToken) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO user (username, email, full_name, password, activation_token) 
            VALUES (:username, :email, :fullname, :hashedPassword, :activationToken)
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $stmt->bindParam(':hashedPassword', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':activationToken', $activationToken, PDO::PARAM_STR);
    $stmt->execute();
}
?>
