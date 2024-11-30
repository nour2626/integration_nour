<?php
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\Controller\UserController.php';
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\config.php';

session_start();

// Check if the user is authenticated for the back-office
if (!isset($_SESSION['back_office_user'])) {
    header('Location: reauthenticate.php');
    exit();
}
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'userName';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';

$offset = ($page - 1) * $limit;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $userController = new UserController();
    $userDetails = $userController->getUser($userId);

    header('Content-Type: application/json');
    if ($userDetails) {
        echo json_encode(['success' => true, 'user' => $userDetails]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>