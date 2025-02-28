<?php
session_start();  // 啟動會話
include '../../common/navbar.php';
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>預約系統</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
    <link rel="stylesheet" href="../../css/snowflake.css?v=<?php echo filemtime('../../css/snowflake.css'); ?>">
    <script src="../../js/snowflake.js?v=<?php echo filemtime('../../js/snowflake.js'); ?>"></script>
</head>
<body>
    <div class="container">
        <h1>歡迎<?php echo $_SESSION['username']; ?>來到預約系統</h1>
        <div class="button-container">
            <a href="page1.php"><button>前往頁面 1</button></a>
            <a href="page2.php"><button>前往頁面 2</button></a>
        </div>
    </div>
    <div class="snowflake-container"></div>
</body>
</html>
