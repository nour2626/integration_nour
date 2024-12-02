<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once '../../../Controller/MessageController.php';

$messageController = new MessageController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senderId = $_SESSION['user']['id'];
    $receiverId = $_POST['receiver_id'];
    $content = $_POST['content'];
    $mediaType = 'text';
    $mediaUrl = null;

    // Handle file upload if any
    if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['media']['tmp_name'];
        $fileName = $_FILES['media']['name'];
        $fileSize = $_FILES['media']['size'];
        $fileType = $_FILES['media']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'gif', 'png', 'mp4', 'avi'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = '../../uploads/';
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $mediaUrl = $newFileName;
                $mediaType = in_array($fileExtension, ['jpg', 'gif', 'png']) ? 'photo' : 'video';
            }
        }
    }

    // Save the message
    $messageController->sendMessage($senderId, $receiverId, $content, $mediaType, $mediaUrl);

    // Redirect back to the inbox
    header('Location: ../inbox.php?receiver_id=' . $receiverId);
    exit();
}
?>