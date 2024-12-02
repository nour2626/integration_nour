<?php
header('Content-Type: application/json');

// Include necessary files and initialize the UserController
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\Controller\UserController.php';
$userController = new UserController();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'userName';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';

try {
    // Fetch users from the controller with pagination and sorting
    $users = $userController->getAllUsers($limit, ($page - 1) * $limit, $sortField, $sortOrder);

    // Return the users as a JSON response
    echo json_encode(['users' => $users]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
