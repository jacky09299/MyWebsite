<?php
require '../../config/config.php';
include '../../common/navbar.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 檢查 token 是否有效
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['password'];
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // 更新密碼並清除 token
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);

            echo "Password has been reset successfully. <a href='login.php'>Login here</a>";
            exit;
        }
    } else {
        echo "Invalid or expired token.";
        exit;
    }
} else {
    echo "No token provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
    <link rel="stylesheet" href="../../css/snowflake.css?v=<?php echo filemtime('../../css/snowflake.css'); ?>">
    <script src="../../css/snowflake.js?v=<?php echo filemtime('../../css/snowflake.js'); ?>"></script>
</head>
<body>
    <h2>Enter New Password</h2>
    <form action="" method="POST">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
    <div class="snowflake-container"></div>
</body>
</html>
