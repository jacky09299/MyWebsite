<?php
session_start();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>多按鈕網頁範例</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo filemtime('../css/style.css'); ?>">
    <link rel="stylesheet" href="../css/snowflake.css?v=<?php echo filemtime('../css/snowflake.css'); ?>">
    <script src="../js/snowflake.js?v=<?php echo filemtime('../js/snowflake.js'); ?>"></script>
    <style>
        .button-group {
    display: flex;
    justify-content: center;
    width: calc(100% - 20px);
    gap: 15px;
    flex-wrap: wrap;
}
.button-group button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    margin: 20px auto;
    white-space: nowrap;
    height: auto;
}
.button-group button:hover {
    background-color: #45a049;
}
    </style>
</head>
<body>
<?include '../common/navbar.php';?>
<div class="button-group">
    <a href="reservation/reservation.php"><button>預約系統</button></a>
    <a href="News/news.php"><button>新聞</button></a>
    <a href="chat/chat_page.php"><button>聊天系統</button></a>
    <a href="editor/new_editor.php"><button>AIT編輯器</button></a>
    <a href="drive/upload.php"><button>雲端硬碟</button></a>
    <a href="AI/ai.php"><button>AI影像辨識</button></a>
</div>
<div class="snowflake-container"></div>
</body>
</html>
