<?php
require '../../config/config.php'; // 包含您的資料庫配置

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 查詢資料庫是否有該 token
    $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // 驗證成功，更新用戶的 verified_at 欄位
        $stmt = $pdo->prepare("UPDATE users SET verified_at = NOW() WHERE verification_token = ?");
        $stmt->execute([$token]);

        echo "Your email has been verified successfully!";
    } else {
        echo "Invalid verification token.";
    }
} else {
    echo "No token provided.";
}
?>
