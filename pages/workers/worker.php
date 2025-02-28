<?php
session_start();
include '../../common/navbar.php';  // 引入共用的導覽列
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>工作人員頁面</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
    <link rel="stylesheet" href="../../css/snowflake.css?v=<?php echo filemtime('../../css/snowflake.css'); ?>">
    <script src="../../js/snowflake.js?v=<?php echo filemtime('../../js/snowflake.js'); ?>"></script>
</head>
<body>
    <h1>歡迎</h1>
    <p>請選擇</p>

    <?php if (isset($_SESSION['user_id'])): ?>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <!-- Link to Admin Page for role management -->
            <p><a href="identity_management.php">身分管理</a></p>
        <?php endif; ?>
        <?php if ($_SESSION['role'] === 'admin' or$_SESSION['role'] === 'staff'): ?>
            <!-- Link to Admin Page for role management -->
            <p><a href="staff.php">訂單管理</a></p>
        <?php endif; ?>
    <?php else: ?>
        <p><a href="../members/login.php">Login to access the reservation page</a></p>
    <?php endif; ?>
    <div class="snowflake-container"></div>
</body>
</html>
