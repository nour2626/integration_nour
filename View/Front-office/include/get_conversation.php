<?php
require_once '../../../config.php';

session_start();

if (!isset($_SESSION['user']['id'])) {
    exit('Unauthorized');
}

$userId = $_SESSION['user']['id'];

if (!isset($_POST['receiverId'])) {
    exit('Error: receiverId not set');
}

$receiverId = $_POST['receiverId'];

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare('SELECT * FROM messages WHERE (sender_id = :userId AND receiver_id = :receiverId) OR (sender_id = :receiverId AND receiver_id = :userId) ORDER BY timestamp ASC');
    $stmt->execute(['userId' => $userId, 'receiverId' => $receiverId]);
    $messages = $stmt->fetchAll();
} catch (Exception $e) {
    exit('Error: ' . $e->getMessage());
}

foreach ($messages as $message) {
    echo '<div class="message">';
    echo '<strong>' . htmlspecialchars($message['sender_id']) . ':</strong> ';
    echo htmlspecialchars($message['content']);
    echo '<br><small>' . htmlspecialchars($message['timestamp']) . '</small>';
    echo '</div>';
}
?>