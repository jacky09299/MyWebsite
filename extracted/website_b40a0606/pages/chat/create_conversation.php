<?php
session_start();
require '../../config/config.php';

$user1_id = $_SESSION['user_id'];
$user2_id = $_GET['receiver_id'];

$stmt = $pdo->prepare("SELECT id FROM conversations WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)");
$stmt->execute([$user1_id, $user2_id, $user2_id, $user1_id]);
$conversation = $stmt->fetch();

if (!$conversation) {
    $stmt = $pdo->prepare("INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)");
    $stmt->execute([$user1_id, $user2_id]);
    $conversation_id = $pdo->lastInsertId();
} else {
    $conversation_id = $conversation['id'];
}

echo json_encode(['conversation_id' => $conversation_id]);
?>
