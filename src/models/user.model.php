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

function createUser($db, $username, $email, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $activationToken = bin2hex(random_bytes(16));
    $query = "INSERT INTO user (username, email, password, activation_token) 
              VALUES (:username, :email, :hashed_password, :activation_token)
             ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':hashed_password', $hashedPassword);
    $stmt->bindParam(':activation_token', $activationToken);
    $stmt->execute();
}

function updateUsername($db, $id, $newUsername) {
    $query = "UPDATE user SET username = :newUsername WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':newUsername', $newUsername);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function updateAvatar($db, $id, $avatar) {
    $query = "UPDATE user SET avatar = :avatar WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':avatar', $avatar);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
?>
