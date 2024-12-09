<?php
// Include the necessary files
require_once(__DIR__ . '/../../../../controller/ReplyController.php');

// Check if reply_id is provided in the URL
if (isset($_GET['reply_id'])) {
    $reply_id = $_GET['reply_id'];

    // Fetch the reply data from the database
    $stmt = $pdo->prepare("SELECT * FROM replies WHERE reply_id = :reply_id");
    $stmt->bindParam(':reply_id', $reply_id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if the reply exists
    if ($stmt->rowCount() > 0) {
        $reply = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Reply not found!";
        exit;
    }

}

// Handle the form submission to update the reply
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_reply_text'], $_POST['reply_id'])) {
        $new_reply_text = $_POST['new_reply_text'];  // Get the new reply text
        $reply_id = $_POST['reply_id'];  // Get the reply ID to identify which reply to update

        // Validate the new reply text
        if (empty($new_reply_text)) {
            echo "Reply text cannot be empty.";
            exit;
        }

        // Create an instance of ReplyController
        $replyController = new ReplyController();

        // Call the editReply method to update the reply
        $response = $replyController->editReply($reply_id, $new_reply_text);

        // Check if the update was successful
        if ($response['status'] === 'success') {
            // Redirect back to the thread page (view_threads.php) after successful update
            header("Location: view_threads.php"); // Redirect after updating
            exit;
        } else {
            echo $response['message']; // Display the error message
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reply</title>
</head>
<body>
    <h2>Edit Reply</h2>
    
    <!-- Form to edit the reply -->
    <form action="edit_reply.php" method="POST">
        <textarea name="new_reply_text" required><?php echo htmlspecialchars($reply['reply_text']); ?></textarea>
        <input type="hidden" name="reply_id" value="<?php echo $reply_id; ?>">
        <button type="submit">Save</button>
    </form>

    <!-- Link to go back to view_threads page --
