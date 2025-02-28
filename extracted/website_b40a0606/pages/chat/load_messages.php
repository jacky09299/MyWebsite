<?php
session_start();
require '../../config/config.php';

if (!isset($_GET['conversation_id']) || empty($_GET['conversation_id'])) {
    throw new Exception('conversation_id is missing or invalid');
}

$conversation_id = $_GET['conversation_id'];
$stmt = $pdo->prepare("SELECT sender_id, message, created_at FROM messages WHERE conversation_id = ? ORDER BY created_at");
$stmt->execute([$conversation_id]);

$messages = '';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $isUserMessage = ($row['sender_id'] == $_SESSION['user_id']);
    $senderClass = $isUserMessage ? 'user-message' : 'partner-message';
    $sender = $isUserMessage ? '你' : '對方';
    
    $messages .= "<div class='message $senderClass'><strong>{$sender}:</strong> " . htmlspecialchars($row['message']) . " <small>{$row['created_at']}</small></div>";
}

echo $messages;
?>
