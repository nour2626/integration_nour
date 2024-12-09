<?php
require_once(__DIR__ . '/../../../../controller/ReplyController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $thread_id = $_POST['thread_id'];
    $reply_text = $_POST['reply_text'];

    $replyController = new ReplyController();
    if ($replyController->addReply($thread_id, $reply_text)) {
        header("Location: thread.php?id=" . $thread_id); // Redirect back to thread page
        exit;
    } else {
        echo "Failed to add reply. Please try again.";
    }
}
