<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/navbar_mobile.css?v=<?php echo filemtime('../../css/navbar_mobile.css'); ?>">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navToggle = document.querySelector('.nav-toggle');
            const navbar = document.querySelector('.navbar');

            navToggle.addEventListener('click', () => {
                navbar.classList.toggle('active');
            });
        });
    </script>
</head>
<body>
    <!-- 導覽按鈕 -->
    <div class="nav-toggle">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- 導覽列 -->
    <div class="navbar">
        <a href="/index.php">首頁</a>
        <a href="/pages/tools_menu.php">工具</a>
        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff')): ?>
            <div class="dropdown">
                <a href="/pages/workers/worker.php">工作人員頁面</a>
                <div class="dropdown-content">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="/pages/workers/identity_management.php">身分管理</a>
                    <?php endif; ?>
                    <a href="/pages/workers/staff.php">訂單管理</a>
                </div>
            </div>
        <?php endif; ?>
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
