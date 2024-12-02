<?php
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\Controller\UserController.php';

session_start();

// Check if the user is authenticated for the back-office
if (!isset($_SESSION['back_office_user'])) {
    header('Location: reauthenticate.php');
    exit();
}

$userController = new UserController();
$users = $userController->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DashBoard</title>
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
                  <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search for users...">
                  <div class="table-responsive">
                      <div class="pagination">
                        <button id="prevPage" class="btn btn-primary">Previous</button>
                        <button id="nextPage" class="btn btn-primary">Next</button>
                      </div>
                    <table class="table table-striped" id="userTable">
                        <thead>
                          <tr>
                            <th> User </th>
                            <th><a href="#" class="sort" data-sort="userName" data-order="asc">First name</a></th>
                            <th><a href="#" class="sort" data-sort="email" data-order="asc">Email</a></th>
                            <th><a href="#" class="sort" data-sort="age" data-order="asc">Age</a></th>
                            <th><a href="#" class="sort" data-sort="created_at" data-order="asc">Created at</a></th>
                            <th><a href="#" class="sort" data-sort="updated_at" data-order="asc">Modified at</a></th>
                            <th> Actions </th>
                          </tr>
                        </thead>
                      <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr id="user-<?php echo $user['id']; ?>">
                          <td class="py-1">
                            <img src="../../uploads/<?php echo $user['photo']; ?>" alt="image" />
                          </td>
                          <td> <?php echo $user['userName']; ?> </td>
                          <td> <?php echo $user['email']; ?> </td>
                          <td> <?php echo $user['age']; ?> </td>
                          <td> <?php echo $user['created_at']; ?> </td>
                          <td> <?php echo $user['updated_at']; ?> </td>
                          <td>
                            <a href="#" class="btn btn-primary edit-user" data-id="<?php echo $user['id']; ?>" data-username="<?php echo $user['userName']; ?>" data-email="<?php echo $user['email']; ?>" data-age="<?php echo $user['age']; ?>">Edit</a>
                            <button class="btn btn-danger delete-user" data-id="<?php echo $user['id']; ?>">Delete</button>
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
      <!-- Edit User Modal -->
      <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="editUserForm">
                <input type="hidden" id="editUserId">
                <div class="form-group">
                  <label for="editUserName">User Name</label>
                  <input type="text" class="form-control" id="editUserName" required>
                </div>
                <div class="form-group">
                  <label for="editUserEmail">Email</label>
                  <input type="email" class="form-control" id="editUserEmail" required>
                </div>
                <div class="form-group">
                  <label for="editUserAge">Age</label>
                  <input type="number" class="form-control" id="editUserAge" required>
                </div>
                <div class="form-group">
                  <label for="editUserPhoto">Profile Photo</label>
                  <input type="file" class="form-control" id="editUserPhoto">
                </div>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <!-- plugins:js -->
    <script src="src/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="src/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="src/assets/js/off-canvas.js"></script>
    <script src="src/assets/js/template.js"></script>
    <script src="src/assets/js/settings.js"></script>
    <script src="src/assets/js/hoverable-collapse.js"></script>
    <script src="src/assets/js/todolist.js"></script>
    <!-- endinject -->
    <script src="src/assets/js/user-list.js"></script>

  </body>



</html>