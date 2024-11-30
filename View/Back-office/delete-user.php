<?php
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\Controller\UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['id'];

    $userController = new UserController();
    $userController->deleteUser($userId);

    echo json_encode(['success' => true]);
}
?>