<?php
require_once '../../../Controller/UserController.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$userController = new UserController();
$users = $userController->getAllUsers($search);

header('Content-Type: application/json');
echo json_encode($users);
?>