<?php

require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\Config.php';
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\Model\User.php';

class UserController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

public function createUser($userName, $age, $email, $password, $photo = null, $role = 'user')
{
    // Check if the email already exists
    $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('Email already exists');
    }

    $user = new User($userName, $age, $email, $password, $photo, $role);
    $user->setPassword(password_hash($password, PASSWORD_BCRYPT));

    $stmt = $this->pdo->prepare('INSERT INTO users (userName, age, email, password, photo, role) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$user->getUserName(), $user->getAge(), $user->getEmail(), $user->getPassword(), $user->getPhoto(), $user->getRole()]);
}

public function getUser($id)
{
    $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log('User retrieved: ' . print_r($user, true)); // Debugging statement
    return $user;
}


public function updateUser($id, $userName, $age, $email, $photo = null)
{
    // Check if the email already exists for a different user
    $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();
    if ($existingUser && $existingUser['id'] != $id) {
        throw new Exception('Email already exists');
    }

    // Fetch the existing user details
    $stmt = $this->pdo->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $existingUser = $stmt->fetch();
    if (!$existingUser) {
        throw new Exception('User not found');
    }

    $password = $existingUser['password']; // Retain the existing password

    $user = new User($userName, $age, $email, $password, $photo);
    $user->setId($id);

    $stmt = $this->pdo->prepare('UPDATE users SET userName = ?, age = ?, email = ?, photo = ? WHERE id = ?');
    $stmt->execute([$user->getUserName(), $user->getAge(), $user->getEmail(), $user->getPhoto(), $user->getId()]);
}

    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
    }
    public function authenticateUser($email, $password) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }

        public function getAllUsers() {
            $stmt = $this->pdo->query('SELECT * FROM users');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>