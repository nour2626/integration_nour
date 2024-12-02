<?php

require_once('config.php');
$replyId = $_GET['reply_id'];  

if ($replyId) {
    try {
        $sql = "DELETE FROM replies WHERE reply_id = :reply_id";
        $db = new config();
        $conn = $db->getConnexion();
        $query = $conn->prepare($sql);
        $query->execute(['reply_id' => $replyId]);

        
        header("Location: view_threads.php");
        exit();

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'No reply ID provided.';
}
?>
