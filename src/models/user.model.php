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

function addUser($db, $username, $password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO user (username, password) VALUES (:username, :hashed_password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':hashed_password', $hashed_password);
    $stmt->execute();
}

function updateUsername($db, $id, $newUsername) {
    $query = "UPDATE user SET username = :newUsername WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':newUsername', $newUsername);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
?>
