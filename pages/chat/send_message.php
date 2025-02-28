<?php
session_start();
require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $conversation_id = $_POST['conversation_id'];
    $sender_id = $_SESSION['user_id'];
    $message = $_POST['message'];

    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO messages (conversation_id, sender_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$conversation_id, $sender_id, $message]);
    }
}
?>
