<?php
session_start();
require_once '../../Controller/UserController.php';

$userController = new UserController();
$data = json_decode(file_get_contents('php://input'), true);
$faceDescriptor = $data['faceDescriptor'];

$user = $userController->getUserByFaceDescriptor($faceDescriptor);

if ($user) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'userName' => $user['userName'],
        'photo' => $user['photo'],
        'email' => $user['email'],
        'age' => $user['age'],
        'role' => $user['Role'],
        'password' => $user['password']
    ];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>