<?php
require_once('../../../../controller/threadcontroller.php');

// Create an instance of the ThreadC class
$threadController = new ThreadC();

// Fetch all threads (both public and private) for admin view
$threads = $threadController->afficherThreads('all'); // Get all threads (public and private)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thread Management</title>
    <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="shortcut icon" href="../../assets/images/favicon.png" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Manage Threads</h4>
                                <p class="card-description">
                                    Add, edit, or delete threads.
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Location</th>
                                                <th>Comment</th>
                                                <th>Visibility</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($threads as $thread): ?>
                                                <tr id="thread-<?= $thread['id'] ?>">
                                                    <td><?= htmlspecialchars($thread['id']) ?></td>
                                                    <td><?= htmlspecialchars($thread['name']) ?></td>
                                                    <td><?= htmlspecialchars($thread['location']) ?></td>
                                                    <td><?= htmlspecialchars($thread['comment']) ?></td>
                                                    <td id="view-<?= $thread['id'] ?>"><?= htmlspecialchars($thread['view']) ?></td>
                                                    <td>
                                                        <a href="edit_thread.php?id=<?= $thread['id'] ?>" class="btn btn-info btn-sm">Edit</a>
                                                        <?php if ($thread['view'] === 'private'): ?>
                                                            <button class="btn btn-success btn-sm" onclick="updateVisibility(<?= $thread['id'] ?>, 'public')">Make Public</button>
                                                        <?php else: ?>
                                                            <button class="btn btn-warning btn-sm" onclick="updateVisibility(<?= $thread['id'] ?>, 'private')">Make Private</button>
                                                        <?php endif; ?>
                                                        <a href="delete_thread.php?id=<?= $thread['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this thread?');">Delete</a>
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
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted">Manage your threads here.</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- JavaScript: Toggle thread visibility (Make Public/Private) -->
    <script>
      function updateVisibility(threadId, newVisibility) {
        $.ajax({
          url: 'update_view.php',
          method: 'GET',
          data: { id: threadId, view: newVisibility },
          success: function(response) {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                $('#view-' + threadId).text(newVisibility);
                const button = $('#thread-' + threadId + ' button');
                if (newVisibility === 'public') {
                    button.text('Make Private').removeClass('btn-success').addClass('btn-warning');
                } else {
                    button.text('Make Public').removeClass('btn-warning').addClass('btn-success');
                }
            } else {
                alert('Error: ' + data.message);
            }
          },
          error: function(xhr, status, error) {
            alert('Error: ' + error);
          }
        });
      }
    </script>

    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../assets/js/template.js"></script>
</body>
</html>
