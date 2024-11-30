<?php

require_once '../../config.php';
require_once '../../Controller/UserController.php';

// Start the session
session_start();

// Check if the user is already authenticated for the back-office
if (isset($_SESSION['back_office_user'])) {
    header('Location: bindex.php');
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $userController = new UserController();
    $userDetails = $userController->authenticateUser($email, $password);

    if ($userDetails) {
        $userDetails = $userController->getUser($userDetails['id']);
        $_SESSION['back_office_user'] = [
            'id' => $userDetails['id'],
            'userName' => $userDetails['userName'],
            'photo' => $userDetails['photo'],
            'email' => $userDetails['email'],
            'age' => $userDetails['age'],
            'password' => $userDetails['password']
        ];
        header('Location: bindex.php');
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Star Admin2 </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="src/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="src/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="src/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="src/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="src/assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="src/assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="src/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="src/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="src/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="src/assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                  <img src="src/assets/images/logo.svg" alt="logo">
                </div>
                <h4>Hello! let's get started</h4>
                <h6 class="fw-light">Sign in to continue.</h6>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form class="pt-3" method="post" action="">
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="exampleInputEmail1" name="email" placeholder="Email" >
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" name="password" placeholder="Password" >
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">SIGN IN</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                    </div>
                    <a href="#" class="auth-link text-black">Forgot password?</a>
                  </div>
                  <div class="mb-2 d-grid gap-2">
                    <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="ti-facebook me-2"></i>Connect using facebook </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="src/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="src/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="src/assets/js/off-canvas.js"></script>
    <script src="src/assets/js/template.js"></script>
    <script src="src/assets/js/settings.js"></script>
    <script src="src/assets/js/hoverable-collapse.js"></script>
    <script src="src/assets/js/todolist.js"></script>
    <!-- endinject -->
  </body>
</html>