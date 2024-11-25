<?php

require_once '../../config.php';
require_once '../../Model/User.php';

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
        return $stmt->fetch();
    }

    public function updateUser($id, $userName, $age, $email, $password, $photo = null,)
    {
        $user = new User($userName, $age, $email, $password, $photo);
        $user->setId($id);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));

        $stmt = $this->pdo->prepare('UPDATE users SET userName = ?, age = ?, email = ?, password = ?, photo = ? WHERE id = ?');
        $stmt->execute([$user->getUserName(), $user->getAge(), $user->getEmail(), $user->getPassword(), $user->getPhoto(), $user->getId()]);
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
}
?>