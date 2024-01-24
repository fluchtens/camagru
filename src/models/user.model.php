<?php
function getUserById($db, $id) {
    $query = "SELECT * FROM user WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($user ? $user : null);
}

function getUserByUsername($db, $username) {
    $query = "SELECT * FROM user WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($user ? $user : null);
}

function getUserByEmail($db, $email) {
    $query = "SELECT * FROM user WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($user ? $user : null);
}

function getUserByActivationToken($db, $activationToken) {
    $query = "SELECT * FROM user WHERE activation_token = :activation_token";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':activation_token', $activationToken);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($user ? $user : null);
}

function createUser($db, $username, $email, $fullname, $password, $activationToken) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO user (username, email, full_name, password, activation_token) 
              VALUES (:username, :email, :full_name, :hashed_password, :activation_token)
             ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':full_name', $fullname);
    $stmt->bindParam(':hashed_password', $hashedPassword);
    $stmt->bindParam(':activation_token', $activationToken);
    $stmt->execute();
}

function updateUsername($db, $id, $newUsername) {
    $query = "UPDATE user SET username = :newUsername WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':newUsername', $newUsername);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function updateFullName($db, $id, $fullname) {
    $query = "UPDATE user SET full_name = :full_name WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':full_name', $fullname);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function updateAvatar($db, $id, $avatar) {
    $query = "UPDATE user SET avatar = :avatar WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':avatar', $avatar);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function updateActiveStatus($db, $id, $active) {
    $query = "UPDATE user SET active = :active WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}
?>
