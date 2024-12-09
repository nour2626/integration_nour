<?php
require('../../../../controller/Replycontroller.php');  // Include your controller file

if (isset($_GET['reply_id'])) {
    $reply_id = $_GET['reply_id'];  // Get the reply ID from the URL

    // Create an instance of the controller
    $threadController = new ReplyController ();

    // Call the function to delete the reply
    $deleted = $threadController->supprimerReply($reply_id);

    // If deleted successfully, redirect to the page with the thread
    if ($deleted) {
        header('Location: view_threads.php');  // Adjust to your desired location after deletion
        exit();
    } else {
        echo "Error deleting reply.";
    }
} else {
    echo "No reply ID provided!";
    exit();
}
?>
