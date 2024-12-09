<?php
session_start();
require_once '../../Controller/UserController.php';

$userController = new UserController();
$data = json_decode(file_get_contents('php://input'), true);
$faceDescriptor = json_encode($data['faceDescriptor']);

$userId = $_SESSION['user']['id'];
if ($userController->saveFaceDescriptor($userId, $faceDescriptor)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>