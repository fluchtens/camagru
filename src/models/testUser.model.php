<?php
class User {
    private $db;
    public $id;
    public $username;
    public $avatar;

    /* ************************************************************************** */
    /*                               Constructor(s)                               */
    /* ************************************************************************** */

    public function __construct($db, $id = null, $username = null) {
        $this->db = $db;
        $this->id = $id;
        $this->username = $username;
        $this->loadInfos();
    }

    /* ************************************************************************** */
    /*                          Private member functions                          */
    /* ************************************************************************** */

    
    private function loadInfos() {
        if ($this->$id) {
            $user = $this->getUserById();
        } else {
            $user = $this->getUserByUsername();
        }
        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->avatar = $user['avatar'];
    }

    private function getUserById() {
        $query = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($user ? $user : null);
    }

    private function getUserByUsername() {
        $query = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($user ? $user : null);
    }

    /* ************************************************************************** */
    /*                          Public member functions                           */
    /* ************************************************************************** */

    public function updateUsername($username) {
        $query = "UPDATE user SET username = :username WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
?>