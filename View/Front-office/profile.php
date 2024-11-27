<?php
session_start(); // Start the session


require_once '../../config.php';
require_once '../../Controller/UserController.php';
if (!isset($_SESSION['user']['id'])) {
    echo 'User is not logged in';
    exit;
}

$userController = new UserController();
$userDetails = $userController->getUser($_SESSION['user']['id']);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $userName = htmlspecialchars(trim($_POST['userName']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $age = filter_var(trim($_POST['age']), FILTER_SANITIZE_NUMBER_INT);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Check if the email already exists for a different user
    if ($userController->emailExists($email) && $email !== $userDetails['email']) {
        $errors[] = "Email already exists.";
    }

    // Handle profile image upload
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
                $errors[] = 'Failed to upload file';
            } else {
                $photo = $photoPath; // Update photo path
            }
        } else {
            $errors[] = 'Invalid file type or size';
        }
    }

    // Update user details if no errors
    if (empty($errors)) {
        $userId = $_SESSION['user']['id'];
        try {
            $userController->updateUser($userId, $userName, $age, $email, $photo);
            // Update session details
            $_SESSION['user']['userName'] = $userName;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['age'] = $age;
            $_SESSION['user']['photo'] = $photo;

            echo 'Profile updated successfully';
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>User Profile</title>
    <!-- Load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tooplate-style.css">
</head>

<body>
    <?php include 'include/header.php'; ?>
    <div class="container tm-pt-5 tm-pb-4">
        <div class="row">
            <div class="col-12">
                <h2 class="tm-section-title">User Profile</h2>
                                    <div class="card-body">
                                        <?php if (!empty($errors)): ?>
                                            <div class="alert alert-danger">
                                                <?php foreach ($errors as $error): ?>
                                                    <p><?php echo $error; ?></p>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                <div class="tm-bg-white tm-p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="<?php echo htmlspecialchars($_SESSION['user']['photo'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded-circle">
                        </div>
                        <div class="col-md-8">
                            <h3><?php echo htmlspecialchars($_SESSION['user']['userName'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p>Email: <?php echo htmlspecialchars($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p>Age: <?php echo htmlspecialchars($_SESSION['user']['age'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p>role: <?php echo htmlspecialchars($_SESSION['user']['role'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="tm-bg-white tm-p-4 mt-4">
                    <h3>Edit Profile</h3>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="userName">Username</label>
                            <input type="text" class="form-control" id="userName" name="userName" value="<?php echo htmlspecialchars($_SESSION['user']['userName'], ENT_QUOTES, 'UTF-8'); ?>" >
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($_SESSION['user']['age'], ENT_QUOTES, 'UTF-8'); ?>" >
                        </div>
                        <div class="form-group">
                            <a href="reset-password.php" class="btn btn-secondary">Reset Password</a>
                        </div>
                        <div class="form-group">
                            <label for="photo">Profile Picture</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Load JS files -->
    <script src="js/jquery-1.11.3.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/datepicker.min.js"></script>
    <script src="js/jquery.singlePageNav.min.js"></script>
    <script src="slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });
    </script>
    <?php include 'include/footer.php'; ?>
</body>
</html>