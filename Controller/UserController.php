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

        public function updateOnlineStatus($userId, $status) {
            $query = "UPDATE users SET is_online = :status WHERE id = :userId";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
        }

    public function getAllUsers($limit = 10, $offset = 0, $sortField = 'userName', $sortOrder = 'asc') {
        $validSortFields = ['userName', 'email', 'age', 'created_at', 'updated_at'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'userName';
        }
        $sortOrder = strtolower($sortOrder) === 'desc' ? 'desc' : 'asc';

        $stmt = $this->pdo->prepare("SELECT * FROM users ORDER BY $sortField $sortOrder LIMIT ? OFFSET ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function banUser($userId) {
        $query = "UPDATE users SET banned = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    public function unbanUser($userId) {
        $query = "UPDATE users SET banned = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    public function saveFaceDescriptor($userId, $faceDescriptor) {
        $stmt = $this->db->prepare("UPDATE users SET face_descriptor = :faceDescriptor WHERE id = :userId");
        return $stmt->execute([':faceDescriptor' => $faceDescriptor, ':userId' => $userId]);
    }
}
?>