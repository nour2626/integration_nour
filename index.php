<?php

require('model/forum.php'); // Include forum.php from the model directory
require('controller/threadcontroller.php'); // Include threadcontroller.php from the controller directory
require('view/create_thread.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $newId = uniqid('thread_', true);
    $name = $_POST['name'];
    $location = $_POST['location'];
    $comment = $_POST['comment'];

    // Create a new Thread object
    $newThread = new Thread($newId, $name, $location, $comment);

    // Create a new ThreadC (controller) object
    $threadController = new ThreadC();

    // Add the new thread to the database
    $threadController->ajouterThread($newThread);

    // Redirect to the main page to display the thread
    header('Location: ../index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="js/validation.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Experiences Forum</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tooplate-style.css">
    <style>
        .navbar-brand {
            color: red !important; /* Change navbar brand text color to red */
            font-weight: bold;
        }
        .btn-outline-primary {
            color: red !important; /* Change button text color to red */
            border-color: red !important; /* Change button border color to red */
        }
        .btn-outline-primary:hover {
            background-color: red !important; /* Red background on hover */
            color: white !important; /* White text on hover */
        }
        .error {
            color: red; /* Error message styling */
            font-size: 0.875rem;
            display: block;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <header class="tm-top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="index.php">Do you have any experience you want to share?</a>
            <!-- Add View All Threads button -->
            <a href="backoffice\dist\pages\tables\view_threads.php" class="btn btn-outline-primary">View All Threads</a>
        </div>
    </header>

    <main class="container mt-4">
        <section class="comment-section">
            <h2>Share Your Travel Experience</h2>
            <form id="travelForm" action="index.php" method="POST">
                <div class="form-group">
                    <label for="user-name">Your Name</label>
                    <input type="text" class="form-control" id="user-name" name="name" placeholder="Enter your name" required>
                    <span class="error" id="nameError"></span>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" placeholder="Where did you travel?" required>
                    <span class="error" id="locationError"></span>
                </div>
                <div class="form-group">
                    <label for="user-comment">Your Experience</label>
                    <textarea class="form-control" id="user-comment" name="comment" rows="5" placeholder="Describe your experience..." required></textarea>
                    <span class="error" id="commentError"></span>
                </div>
                <button type="submit" class="btn btn-primary">Post Your Story</button>
            </form>
        </section>
    </main>

    <footer class="tm-bg-dark-blue p-4 text-center">
        <p class="tm-font-light tm-color-white">
            Copyright &copy; <?= date('Y') ?> Travel Forum. All rights reserved.
        </p>
    </footer>
</body>
</html>
