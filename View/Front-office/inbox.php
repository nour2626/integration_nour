<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once '../../Controller/MessageController.php';
require_once '../../Controller/UserController.php';

$userController = new UserController();
$messageController = new MessageController();

$users = $userController->getAllUsers();
$messages = [];

if (isset($_GET['receiver_id'])) {
    $receiverId = $_GET['receiver_id'];
    $messages = $messageController->getMessages($_SESSION['user']['id'], $receiverId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'include/header.php'; ?>


<div class="header">
    <h1 id="header-title">Messenger</h1>
</div>

    <div class="chat-container">
        <!-- User list and search -->
<div class="contacts" id="contactlist">
    <form method="GET" action="inbox.php">
        <input type="text" id="search" class="form-control mb-3" placeholder="Search for users...">
    </form>
    <ul>
        <?php foreach ($users as $user): ?>
            <li onclick="updateHeader('<?php echo htmlspecialchars($user['userName']); ?>')">
                <a href="inbox.php?receiver_id=<?php echo $user['id']; ?>">
                    <img src="../../uploads/<?php echo $user['photo']; ?>" alt="image">
                    <div>
                        <strong><?php echo htmlspecialchars($user['userName']); ?></strong>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

        <div class="chat">
            <div class="messages">
                <?php foreach ($messages as $message): ?>
                    <div class="message <?php echo $message['sender_id'] == $_SESSION['user']['id'] ? 'sent' : 'received'; ?>">
                        <p><strong><?php echo htmlspecialchars($message['sender_name']); ?>:</strong></p>
                        <p><?php echo htmlspecialchars($message['content']); ?></p>
                        <span class="timestamp"><?php echo $message['timestamp']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="input-area">
                <form action="include/send_message.php" method="POST" enctype="multipart/form-data">
                    <label for="image-upload" class="upload-icon">
                        <i class="fas fa-image"></i>
                    </label>
                    <input type="file" id="image-upload" name="media" class="file-input" accept="image/*">

                    <label for="video-upload" class="upload-icon">
                        <i class="fas fa-video"></i>
                    </label>
                    <input type="file" id="video-upload" name="media" class="file-input" accept="video/*">

                    <textarea name="content" placeholder="Type a message..."></textarea>
                    <button type="submit">Send</button>
                    <input type="hidden" name="receiver_id" value="<?php echo isset($receiverId) ? $receiverId : ''; ?>">
                </form>
            </div>
        </div>
    </div>

</body>
</html>

<script>
document.getElementById('search').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#contactlist li');

    rows.forEach(row => {
        const cells = row.querySelectorAll('div');
        const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchValue));
        row.style.display = match ? '' : 'none';
    });
});

function updateHeader(userName) {
    document.getElementById('header-title').textContent = userName;
}
</script>