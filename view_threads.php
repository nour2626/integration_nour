<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Travel Experiences Forum</title>
  <!-- Plugins:css -->
  <link rel="stylesheet" href="assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="shortcut icon" href="assets/images/favicon.png" />
  <style>
    /* Red theme styling */
    .navbar {
      background-color: #d9534f;
      color: white;
      padding: 15px;
    }

    .navbar a {
      color: white;
      font-weight: bold;
      text-decoration: none;
    }

    .navbar a:hover {
      text-decoration: underline;
    }

    .content-wrapper {
      background-color: #fdf7f7;
      padding: 20px;
    }

    .thread-item {
      margin-bottom: 20px;
      background-color: #ffe6e6;
      padding: 15px;
      border-radius: 8px;
      border: 1px solid #d9534f;
    }

    .thread-item h4 {
      font-size: 1.5rem;
      font-weight: bold;
      color: #d9534f;
      margin-bottom: 10px;
    }

    .thread-item p {
      font-size: 1rem;
      margin-bottom: 5px;
    }

    .thread-item .location {
      color: #6c757d;
      font-style: italic;
    }

    .footer {
      background-color: #d9534f;
      color: white;
      padding: 10px;
      text-align: center;
    }

    .footer span {
      color: white;
    }
  </style>
</head>
<body>
  <div class="container-scroller">
    <header class="navbar">
      <a href="index.php">Back to Home Page</a>
    </header>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <h2 class="text-center" style="color: #d9534f;">Travel Experiences Shared by Users</h2>
          <p class="text-center">Browse through amazing travel stories and experiences shared by others!</p>

          <div id="thread-container" class="mt-4">
            <?php
            require_once('../../../../controller/threadcontroller.php');
            $threadController = new ThreadC();
            $threads = $threadController->afficherThreads(); // Fetch public threads

            if (!empty($threads)) {
              foreach ($threads as $thread) {
                echo '<div class="thread-item">';
                echo '<h4>' . htmlspecialchars($thread['name']) . '</h4>';
                echo '<p class="location"><strong>Location:</strong> ' . htmlspecialchars($thread['location']) . '</p>';
                echo '<p class="comment">' . htmlspecialchars($thread['comment']) . '</p>';
                echo '</div>';
              }
            } else {
              echo '<p style="color: #d9534f;">No public threads available. Be the first to share your travel experience!</p>';
            }
            ?>
          </div>
        </div>
        <!-- Footer -->
        <footer class="footer">
          <span>Travel Forum. All rights reserved © <?= date('Y') ?>.</span>
        </footer>
      </div>
    </div>
  </div>
  <!-- Plugins:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/todolist.js"></script>
</body>
</html>
