<?php
session_start();
require 'path/to/UserController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $email = $_SESSION['email'];

    // Create an instance of UserController
    $userController = new UserController();

    // Update the password using the UpdateUser function
    if ($userController->UpdateUser($email, $new_password)) {
        echo 'Password has been reset successfully.';
    } else {
        echo 'Failed to reset password.';
    }
}
?>