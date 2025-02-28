<?php
session_start();
include '../../common/navbar.php';  // 引入共用的導覽列
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>頁面 2 預約</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
    <link rel="stylesheet" href="../../css/snowflake.css?v=<?php echo filemtime('../../css/snowflake.css'); ?>">
    <script src="../../js/snowflake.js?v=<?php echo filemtime('../../js/snowflake.js'); ?>"></script>
    <script src="../../js/calendar.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>頁面 2 預約</h1>
        <div class="controls">
            <label for="year">選擇年份:</label>
            <select id="year"></select>
            <label for="month">選擇月份:</label>
            <select id="month"></select>
        </div>
        <div id="calendar"></div>
        <button id="confirmButton" style="display:none;" onclick="confirmReservation('reserve.php', 'page2')">確認預約</button>
        <input type="hidden" id="selectedDate">
    </div>
    <p><a href="reservation.php">回上一頁</a></p>
</body>
</html>
