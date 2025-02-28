<?php
require '../../config/config.php';
include '../../common/navbar.php';  // 引入共用的導覽列

// 開啟錯誤顯示
ini_set('display_errors', 1); 
error_reporting(E_ALL);

// 顯示 HTML 頁面結構
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>重設密碼</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
</head>
<body>
    <h2>重設密碼</h2>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 取得表單中的電子郵件
        $email = $_POST['email'];

        // 檢查電子郵件是否存在於資料庫中
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // 生成一個重設密碼的 Token
            $resetToken = bin2hex(random_bytes(16));
            $resetTokenExpiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token 1 小時後過期

            // 更新資料庫中的 reset_token 和過期時間
            $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
            $stmt->execute([$resetToken, $resetTokenExpiry, $email]);

            // 創建重設連結
            $resetLink = "https://website-b40a0606.hgp.qtb.mybluehost.me/pages/members/reset_form.php?token=$resetToken";

            // 設定郵件內容
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Click the link below to reset your password:\n\n" . $resetLink;
            $headers = "From: hgpqtbmy@hgp.qtb.mybluehost.me" . "\r\n" .
                       "Reply-To: hgpqtbmy@hgp.qtb.mybluehost.me" . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();

            // 發送郵件
            if (mail($to, $subject, $message, $headers)) {
                echo "<p>重設密碼的郵件已成功發送。</p>";
            } else {
                echo "<p>無法發送重設密碼郵件。</p>";
            }
        } else {
            echo "<p>找不到該電子郵件。</p>";
        }
    }
    ?>
    <form action="" method="POST">
        <label for="email">電子郵件：</label>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">發送重設郵件</button>
    </form>
    <p><a href="login.php">返回登入</a></p>
</body>
</html>
