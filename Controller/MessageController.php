<?php
require_once 'C:\xampp\htdocs\myproject\web\Projectweb25\config.php';

class MessageController {
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

    public function getMessages($senderId, $receiverId) {
        $stmt = $this->pdo->prepare("
            SELECT
                m.id,
                m.subject,
                m.content,
                m.media_type,
                m.media_url,
                m.timestamp,
                sender.userName AS sender_name,
                sender.email AS sender_email,
                receiver.userName AS receiver_name,
                receiver.email AS receiver_email
            FROM
                messages m
            JOIN
                users sender ON m.sender_id = sender.id
            JOIN
                users receiver ON m.receiver_id = receiver.id
            WHERE
                (m.sender_id = :senderId AND m.receiver_id = :receiverId)
                OR (m.sender_id = :receiverId AND m.receiver_id = :senderId)
            ORDER BY
                m.timestamp DESC
        ");
        $stmt->execute(['senderId' => $senderId, 'receiverId' => $receiverId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage($senderId, $receiverId, $content, $mediaType, $mediaUrl) {
        $stmt = $this->pdo->prepare('
            INSERT INTO messages (sender_id, receiver_id, content, media_type, media_url, timestamp)
            VALUES (?, ?, ?, ?, ?, NOW())
        ');
        $stmt->execute([$senderId, $receiverId, $content, $mediaType, $mediaUrl]);
    }
    public function getMessagesAjax() {

        if (isset($_GET['receiver_id']) && isset($_SESSION['user']['id'])) {
            $receiverId = $_GET['receiver_id'];
            $senderId = $_SESSION['user']['id'];
            $messages = $this->getMessages($senderId, $receiverId);
            echo json_encode($messages);
        }
    }
}
?>