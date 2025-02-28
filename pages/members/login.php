<?php
session_start();

require '../../config/config.php';
include '../../common/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 收集用戶輸入
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 查詢用戶
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // 登入成功，將用戶資料存入 session
        $_SESSION['user_id'] = $user['id'];  // 記錄用戶 ID
        $_SESSION['username'] = $user['username'];  // 記錄用戶名稱
        $_SESSION['role'] = $user['role'];


        // 登入成功後，重定向回首頁
        header("Location: ../../index.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
    <link rel="stylesheet" href="../../css/snowflake.css?v=<?php echo filemtime('../../css/snowflake.css'); ?>">
    <script src="../../css/snowflake.js?v=<?php echo filemtime('../../css/snowflake.js'); ?>"></script>
</head>
<body>
    <?php include '../../common/../../common/navbar.php'; ?>
    <h2>Login</h2>
    <form action="../members/login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <p><a href="register.php">Create an account</a></p>
    <p><a href="reset_password.php">Forgot your password?</a></p>
    <div class="snowflake-container"></div>
</body>
</html>
