<?php
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\Controller\UserController.php';
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\config.php';

session_start();

// Check if the user is authenticated for the back-office
if (!isset($_SESSION['back_office_user'])) {
    header('Location: reauthenticate.php');
    exit();  // No further code execution, this ensures no unwanted output
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id']; // Changed from $_GET to $_POST
    $userName = htmlspecialchars(trim($_POST['userName']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $age = filter_var(trim($_POST['age']), FILTER_SANITIZE_NUMBER_INT);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    $userController = new UserController();
    $userDetails = $userController->getUser($userId);

    // Check if the email already exists for a different user
    if ($userController->emailExists($email) && $email !== $userDetails['email']) {
        $errors[] = "Email already exists.";
    }

    // Handle profile image upload
    $photo = $userDetails['photo']; // Keep current photo if no new upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['photo']['tmp_name']);
        $fileExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        if (in_array($fileType, $allowedTypes) && $_FILES['photo']['size'] <= 1048576 && in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $uploadDir = 'uploads/';
            $photoPath = $uploadDir . uniqid('profile_', true) . '.' . $fileExtension;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                $errors[] = 'Failed to upload file';
            } else {
                $photo = $photoPath; // Update photo path
            }
        } else {
            $errors[] = 'Invalid file type or size';
        }
    }

    // Update user details if no errors
    header('Content-Type: application/json'); // Ensure JSON response for all outcomes
    if (empty($errors)) {
        $result = $userController->updateUser($userId, $userName, $age, $email, $photo);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating user']);
        }
    } else {
        echo json_encode(['success' => false, 'errors' => $errors]);
    }

    exit();  // Prevent further output or any unwanted behavior
}
