<?php
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\Controller\UserController.php';

$userController = new UserController();
$users = $userController->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DashBoard </title>
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
      <!-- partial:../../partials/_navbar.html -->
          <?php include 'src/partials/_navbar.php'; ?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
            <?php include 'src/partials/_sidebar.php'; ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
              <div class="col-lg-12 grid-margin stretch-card">
                              <div class="card">
                                <div class="card-body">
                                  <h4 class="card-title">Users List</h4>
                                  <p class="card-description"> Add class <code>.table-striped</code>
                                  </p>
                                  <div class="table-responsive">
                                    <table class="table table-striped">
                                      <thead>
                                        <tr>
                                          <th> User </th>
                                          <th> First name </th>
                                          <th> Email </th>
                                          <th> Age </th>
                                          <th> Created at </th>
                                          <th> Modified at </th>
                                          <th> Edit </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php foreach ($users as $user): ?>
                                        <tr>
                                          <td class="py-1">
                                              <img src="C:\xampp\htdocs\myproject\web\Projectweb25\uploads\<?php echo $user['image']; ?>" alt="image" />                                          </td>
                                          <td> <?php echo $user['first_name']; ?> </td>
                                          <td> <?php echo $user['email']; ?> </td>
                                          <td> <?php echo $user['age']; ?> </td>
                                          <td> <?php echo $user['created_at']; ?> </td>
                                          <td> <?php echo $user['modified_at']; ?> </td>
                                          <td>
                                            <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary">Edit</a>
                                          </td>
                                        </tr>
                                        <?php endforeach; ?>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
            <?php include 'src/partials/_footer.php'; ?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
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
    <!-- Custom js for this page-->
    <!-- End custom js for this page-->
  </body>
</html>