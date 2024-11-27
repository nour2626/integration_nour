<?php

require_once '../../config.php';
require_once '../../Controller/UserController.php';

// Check if the user is logged in
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php'); // Redirect to the home page or another page
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = trim($_POST['userName']);
    $age = trim($_POST['age']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);
    $photo = isset($_FILES['photo']) ? $_FILES['photo']['name'] : null;

    $errors = [];

    // Validate username
    if (empty($userName)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $userName)) {
        $errors[] = "Username must be 3-20 characters long and can only contain letters, numbers, and underscores.";
    }

    // Validate age
    if (empty($age)) {
        $errors[] = "Age is required.";
    } elseif (!filter_var($age, FILTER_VALIDATE_INT) || $age < 1 || $age > 120) {
        $errors[] = "Age must be a valid number between 1 and 120.";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        $userController = new UserController();
        if ($userController->emailExists($email)) {
            $errors[] = "Email already exists.";
        }
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    } elseif ($password !== $cpassword) {
        $errors[] = "Passwords do not match.";
    }

        $photo = $userDetails['photo']; // Keep current photo if no new upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            // Validate file type and size (example: max 1MB and only JPG, PNG, or GIF files)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['photo']['tmp_name']);
            $fileExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            if (in_array($fileType, $allowedTypes) && $_FILES['photo']['size'] <= 1048576 && in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Generate a unique file name and save the file
                $photoPath = 'uploads/' . uniqid('profile_', true) . '.' . $fileExtension;
                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                    echo 'Failed to upload file';
                    exit;
                }
                $photo = $photoPath; // Update photo path
            } else {
                echo 'Invalid file type or size';
                exit;
            }
        }

    if (empty($errors)) {
        $userController = new UserController();

        try {
            $userController->createUser($userName, $age, $email, $password, $photo);
            header('Location: login.php'); // Redirect to login page after successful registration
            exit();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
<?php include 'include/header.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header bg-primary text-white">
                        <h2>Inscription</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error): ?>
                                    <p><?php echo $error; ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="userName">Username:</label>
                                <input type="text" id="userName" name="userName" class="form-control" placeholder="Your name">
                            </div>
                            <div class="form-group">
                                <label for="age">Age:</label>
                                <input type="number" id="age" name="age" class="form-control" placeholder="Age">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="email" >
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="enter password" >
                            </div>
                            <div class="form-group">
                                <label for="password">Confirm Password:</label>
                                <input type="password" id="password" name="cpassword" class="form-control" placeholder="confirm password" >
                            </div>
                            <div class="form-group">
                                <label for="photo">Photo (optional):</label>
                                <input type="file" id="photo" name="photo" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include 'include/footer.php'; ?>
</body>
</html>