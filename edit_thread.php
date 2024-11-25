<?php
require_once('../../../../controller/threadcontroller.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base URL
define('BASE_URL', '/web_project/backoffice/dist/pages/tables/');

// Get current file name
$current_file = basename(__FILE__);

// Fetch thread ID from the URL
if (isset($_GET['id'])) {
    $threadId = $_GET['id'];
} else {
    die("Thread ID not provided.");
}


$threadController = new ThreadC();


$thread = $threadController->recupererThread($threadId);
if (!$thread) {
    die("Thread not found.");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Form submitted - Processing update for thread ID: " . $threadId);
    
   
    $name = $_POST['name'];
    $location = $_POST['location'];
    $comment = $_POST['comment'];

    // Create a new Thread 
    $updatedThread = new Thread($threadId, $name, $location, $comment);

    // Update the thread 
    try {
        if ($threadController->modifierThread($updatedThread, $threadId)) {
            error_log("Update successful - Redirecting to basic-table.php");
            header("Location: " . BASE_URL . "basic-table.php");
            exit();
        } else {
            error_log("Update failed for thread ID: " . $threadId);
            echo "Error updating thread";
        }
    } catch (Exception $e) {
        error_log("Exception occurred: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Thread</title>
    <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit Thread</h4>
                                <form id="editForm" action="<?= $current_file ?>?id=<?= $threadId ?>" method="POST">
                                    <div class="form-group">
                                        <label for="name">Thread Name</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               value="<?= htmlspecialchars($thread['name']) ?>" 
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="location" 
                                               name="location" 
                                               value="<?= htmlspecialchars($thread['location']) ?>" 
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label for="comment">Comment</label>
                                        <textarea class="form-control" 
                                                  id="comment" 
                                                  name="comment" 
                                                  rows="5" 
                                                  required><?= htmlspecialchars($thread['comment']) ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="<?= BASE_URL ?>basic-table.php" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted">Travel Forum. All rights reserved © <?= date('Y') ?>.</span>
                </div>
            </footer>
        </div>
    </div>

    
    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../assets/js/template.js"></script>
    
  
    <script>
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        
        const formData = new FormData(this);
        
       
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
         
            window.location.href = '<?= BASE_URL ?>basic-table.php';
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    </script>
</body>
</html>