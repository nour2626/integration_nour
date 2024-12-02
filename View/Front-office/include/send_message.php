<?php
// inbox.php
session_start();
include('config.php');

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id']; // Current logged-in user (using 'id' from session)
$other_user_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;

if (!$other_user_id) {
    die('Receiver user ID is missing.');
}

// Fetch messages exchanged with the other user
$pdo = config::getConnexion();
$sql = "SELECT m.id, m.sender_id, m.receiver_id, m.content, m.media_type, m.media_url, m.timestamp,
               u1.userName AS sender_name, u2.userName AS receiver_name
        FROM messages m
        JOIN users u1 ON m.sender_id = u1.id
        JOIN users u2 ON m.receiver_id = u2.id
        WHERE (m.sender_id = :user_id AND m.receiver_id = :other_user_id)
           OR (m.sender_id = :other_user_id AND m.receiver_id = :user_id)
        ORDER BY m.timestamp ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id, 'other_user_id' => $other_user_id]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="css/styles.css">
    <!-- Add FontAwesome CDN for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>

    <div class="header">
        <h1>Messenger</h1>
    </div>

    <div class="chat-container">
        <div class="contacts">
            <!-- This section can be populated dynamically with contacts -->
            <ul>
                <li>
                    <img src="https://via.placeholder.com/40" alt="Contact 1">
                    <div>
                        <strong>John Doe</strong>
                        <p>Last message from John...</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="chat">
            <div class="messages">
                <?php foreach ($messages as $message): ?>
                    <div class="message <?= ($message['sender_id'] == $user_id) ? 'sent' : 'received' ?>">
                        <p><strong><?= htmlspecialchars($message['sender_name']) ?>:</strong></p>
                        <?php if ($message['media_type'] == 'photo'): ?>
                            <img src="<?= htmlspecialchars($message['media_url']) ?>" alt="Image" class="message-media">
                        <?php elseif ($message['media_type'] == 'video'): ?>
                            <video controls class="message-media">
                                <source src="<?= htmlspecialchars($message['media_url']) ?>" type="video/mp4">
                            </video>
                        <?php endif; ?>
                        <p><?= htmlspecialchars($message['content']) ?></p>
                        <span class="timestamp"><?= $message['timestamp'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="input-area">
                <form action="send_message.php" method="POST" enctype="multipart/form-data">
                    <!-- Image upload icon -->
                    <label for="image-upload" class="upload-icon">
                        <i class="fas fa-image"></i>
                    </label>
                    <input type="file" id="image-upload" name="media" class="file-input" accept="image/*">

                    <!-- Video upload icon -->
                    <label for="video-upload" class="upload-icon">
                        <i class="fas fa-video"></i>
                    </label>
                    <input type="file" id="video-upload" name="media" class="file-input" accept="video/*">

                    <!-- Textarea for typing the message -->
                    <textarea name="content" placeholder="Type a message..."></textarea>
                    <button type="submit">Send</button>
                    <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($other_user_id) ?>">
                </form>
            </div>
        </div>
    </div>

</body>
</html>
