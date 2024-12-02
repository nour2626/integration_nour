<?php
// Start the session
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to reply.";
    exit();
}

// Include necessary files
include_once('../../../../controller/threadcontroller.php');
include_once('../../../../model/forum.php');
include_once('../../../../config.php');

// Initialize thread controller
$threadController = new ThreadC();

// Get the thread ID from the URL (required to link the reply to the correct thread)
$threadId = $_GET['id'] ?? '';  // Get the thread ID from the URL parameter

// Ensure the thread ID is present
if (!$threadId) {
    echo "Thread ID is missing!";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $replyText = $_POST['reply_text'] ?? '';  // Get the reply text from the form

    // Ensure reply text is not empty
    if (!empty($replyText)) {
        $userId = $_SESSION['user_id'];  // Get the user ID from the session
        // Add the reply to the database
        $threadController->addReply($userId, $threadId, $replyText);

        // Redirect back to the view threads page after submitting the reply
        header('Location: view_threads.php');  // Redirect to the threads page
        exit();
    } else {
        echo "Please write a reply.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to the Thread</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #d9534f;
        }
        .reply-form textarea {
            width: 100%;
            padding: 10px;
            height: 150px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .reply-form button {
            background-color: #d9534f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .reply-form button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Reply to the Thread</h1>

    <!-- Form to submit reply -->
    <form method="POST" class="reply-form">
        <textarea name="reply_text" placeholder="Write your reply..." required></textarea><br>
        <button type="submit">Submit Reply</button>
    </form>
</div>

</body>
</html>
