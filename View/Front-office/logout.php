<?php
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\Controller\UserController.php';
session_start(); // Start the session
$userId = $_SESSION['user']['id'];
$userController = new UserController();
$userController->updateOnlineStatus($userId, false);
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

header('Location: index.php'); // Redirect to the home page or another page
exit();
?>