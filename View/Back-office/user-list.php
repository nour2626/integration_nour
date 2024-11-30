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
    <script>
          let currentPage = 1;
          const limit = 10;
          let sortField = 'userName';
          let sortOrder = 'asc';

          document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
              currentPage--;
              loadUsers();
            }
          });

          document.getElementById('nextPage').addEventListener('click', () => {
            currentPage++;
            loadUsers();
          });

document.querySelectorAll('.sort').forEach(header => {
  header.addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default anchor behavior
    sortField = this.getAttribute('data-sort');
    sortOrder = this.getAttribute('data-order') === 'asc' ? 'desc' : 'asc';
    this.setAttribute('data-order', sortOrder);
    loadUsers();
  });
});

function loadUsers() {
  fetch(`get-users.php?page=${currentPage}&limit=${limit}&sort=${sortField}&order=${sortOrder}`)
    .then(response => response.json())
    .then(data => {
      const tbody = document.querySelector('#userTable tbody');
      tbody.innerHTML = '';
      data.users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td class="py-1"><img src="../../uploads/${user.photo}" alt="image" /></td>
          <td>${user.userName}</td>
          <td>${user.email}</td>
          <td>${user.age}</td>
          <td>${user.created_at}</td>
          <td>${user.updated_at}</td>
          <td>
            <a href="#" class="btn btn-primary edit-user" data-id="${user.id}" data-username="${user.userName}" data-email="${user.email}" data-age="${user.age}">Edit</a>
            <button class="btn btn-danger delete-user" data-id="${user.id}">Delete</button>
          </td>
        `;
        tbody.appendChild(row);
      });
    });
}

loadUsers();



      document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#userTable tbody tr');

        rows.forEach(row => {
          const cells = row.querySelectorAll('td:not(.py-1)');
          const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchValue));
          row.style.display = match ? '' : 'none';
        });
      });

      document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', function() {
          const userId = this.getAttribute('data-id');
          if (confirm('Are you sure you want to delete this user?')) {
            fetch('delete-user.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ id: userId })
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                document.getElementById('user-' + userId).remove();
              } else {
                alert('Error deleting user');
              }
            });
          }
        });
      });

      document.querySelectorAll('.edit-user').forEach(button => {
        button.addEventListener('click', function() {
          const userId = this.getAttribute('data-id');
          const userName = this.getAttribute('data-username');
          const userEmail = this.getAttribute('data-email');
          const userAge = this.getAttribute('data-age');

          document.getElementById('editUserId').value = userId;
          document.getElementById('editUserName').value = userName;
          document.getElementById('editUserEmail').value = userEmail;
          document.getElementById('editUserAge').value = userAge;

          $('#editUserModal').modal('show');
        });
      });

      document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const userId = document.getElementById('editUserId').value;
        const userName = document.getElementById('editUserName').value;
        const userEmail = document.getElementById('editUserEmail').value;
        const userAge = document.getElementById('editUserAge').value;
        const userPhoto = document.getElementById('editUserPhoto').files[0];

        const formData = new FormData();
        formData.append('userName', userName);
        formData.append('email', userEmail);
        formData.append('age', userAge);
        if (userPhoto) {
            formData.append('photo', userPhoto);
        }

        fetch('update-user.php?id=' + userId, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User updated successfully');
                location.reload();
            } else {
                alert('Error updating user: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
      });
    </script>

  </body>

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

</html>