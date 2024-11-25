<?php
require('../../../../controller/threadcontroller.php'); // Include the thread controller

// Check if the thread ID is provided via GET
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get the thread ID from the URL

    // Create an instance of the Thread controller
    $threadController = new ThreadC();

    // Call the delete method in the Thread controller
    $threadController->supprimerThread($id);

    // Redirect back to the threads list page after deletion
    header('Location: basic-table.php');
    exit();
} else {
    // If no thread ID is provided, redirect back with an error
    echo "No thread ID provided!";
    exit();
}
?>