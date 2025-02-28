<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
</head>
<body>
    <div class="navbar">
        <!-- 1. 首頁 -->
        <a href="/index.php">首頁</a>

        <!-- 2. 預約系統 -->
        <a href="/pages/tools_menu.php">工具</a>

        <!-- 3. 工作人員頁面及選單 (admin 和 staff 角色可見) -->
        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff')): ?>
            <div class="dropdown">
                <a href="/pages/workers/worker.php">工作人員頁面</a>
                <div class="dropdown-content">
                    <!-- i. 身分管理 (僅 admin 可見) -->
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="/pages/workers/identity_management.php">身分管理</a>
                    <?php endif; ?>
                    <!-- ii. 訂單管理 (admin 和 staff 可見) -->
                    <a href="/pages/workers/staff.php">訂單管理</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- 4. 帳戶選單 -->
        <div class="dropdown">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#">帳戶</a>
                <div class="dropdown-content">
                    <a href="/pages/members/logout.php">登出</a>
                </div>
            <?php else: ?>
                <a href="#">帳戶選單</a>
                <div class="dropdown-content">
                    <a href="/pages/members/login.php">登入</a>
                    <a href="/pages/members/register.php">註冊</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
