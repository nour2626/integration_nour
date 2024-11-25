<?php
require_once('../../../../controller/threadcontroller.php');  // Include the thread controller

// Ensure both the ID and new visibility value are provided
if (isset($_GET['id']) && isset($_GET['view'])) {
    $id = $_GET['id']; // Thread ID
    $view = $_GET['view']; // New visibility status ('public' or 'private')

    // Create an instance of the ThreadC class
    $threadController = new ThreadC();

    // Validate that the visibility is either 'public' or 'private'
    if ($view !== 'public' && $view !== 'private') {
        // If the view is invalid, return an error
        echo json_encode(['status' => 'error', 'message' => 'Invalid view status.']);
        exit;
    }

    // Update the thread's visibility status
    $threadController->updateViewStatus($id, $view);
    
    // Return success response
    echo json_encode(['status' => 'success', 'message' => 'Visibility updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters.']);
}
?>
