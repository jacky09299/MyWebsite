<?php
session_start();
require '../../config/config.php';
include '../../common/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../members/login.php");
    exit();
}

// 取得好友清單
$stmt = $pdo->query("SELECT id, username FROM users WHERE id != " . $_SESSION['user_id']);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>聊天室</title>
    <style>
    #container { display: flex; }
    #user-list { width: 30%; padding: 10px; border-right: 1px solid #ccc; }
    #chat-area { width: 70%; padding: 10px; }
    .user-item { padding: 8px; cursor: pointer; border-bottom: 1px solid #ddd; }
    .user-item:hover { background-color: #f0f0f0; }
    /* 聊天框的樣式 */
#chat-box {
    width: 100%;
    height: 300px;
    overflow-y: auto;
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
}

/* 自己訊息置左 */
.partner-message {
    text-align: left;
    background-color: #e1f5fe;
    align-self: flex-start;
    display: inline-block;
    max-width: 33%; /* 設定最大寬度為聊天框的 1/3 */
    word-wrap: break-word; /* 超過最大寬度時自動換行 */
    padding: 8px 12px; /* 訊息框的內距 */
    margin: 4px 0;
    border-radius: 10px; /* 圓角設計 */
}

/* 對方訊息置右 */
.user-message {
    text-align: right;
    background-color: #ffe0b2;
    align-self: flex-end;
    display: inline-block;
    max-width: 33%; /* 同樣設定為 1/3 的最大寬度 */
    word-wrap: break-word;
    padding: 8px 12px;
    margin: 4px 0;
    border-radius: 10px;
}


    #message-form { display: flex; }
    #message { flex: 1; padding: 10px; margin-right: 5px; }
    #send-btn { padding: 10px; }
</style>

</head>
<body>
    <h2>聊天室</h2>
    <div id="container">
        <!-- 好友清單區域 -->
        <div id="user-list">
            <h3>好友清單</h3>
            <?php foreach ($users as $user): ?>
                <div class="user-item" onclick="selectUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">
                    <?php echo htmlspecialchars($user['username']); ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- 聊天區域 -->
        <div id="chat-area">
            <h3 id="chat-with">選擇一位好友來聊天</h3>
            <div id="chat-box"></div>
            <form id="message-form" style="display:none;">
                <input type="hidden" id="conversation-id">
                <input type="text" id="message" placeholder="輸入訊息" required>
                <button type="button" id="send-btn">發送</button>
            </form>
        </div>
    </div>

    <script>
        let conversationId = null;
        function scrollToBottom() {
    document.getElementById("chat-box").scrollTop = document.getElementById("chat-box").scrollHeight;
}

        function selectUser(userId, username) {
            // 更新選擇的聊天對象
            document.getElementById("chat-with").textContent = `與 ${username} 聊天`;
            document.getElementById("message-form").style.display = 'flex';

            // 初始化對話，創建或加載對話 ID
            fetch(`create_conversation.php?receiver_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    conversationId = data.conversation_id;
                    loadMessages();  // 加載訊息
                    setTimeout(scrollToBottom, 100);
                });
        }

        function loadMessages() {
    if (conversationId) {
        fetch(`load_messages.php?conversation_id=${conversationId}`)
            .then(response => response.text())
            .then(data => {
                console.log("Loaded messages:", data); // 查看回傳的資料
                document.getElementById("chat-box").innerHTML = data;
            });
    }
}


        // 定時加載訊息
        setInterval(loadMessages, 2000);

        document.getElementById("send-btn").addEventListener("click", function() {
            const message = document.getElementById("message").value.trim();

            if (message && conversationId) {
                fetch("send_message.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `conversation_id=${encodeURIComponent(conversationId)}&message=${encodeURIComponent(message)}`
                }).then(() => {
                    document.getElementById("message").value = "";
                    loadMessages();
                    setTimeout(scrollToBottom, 100);
                });
            }
        });
        document.getElementById("message").addEventListener("keydown", function(event) {
    const messageBox = document.getElementById("message");

    // Shift + Enter 插入換行
    if (event.key === "Enter" && event.shiftKey) {
        event.preventDefault(); // 阻止默認行為
        const cursorPosition = messageBox.selectionStart;
        messageBox.value = messageBox.value.slice(0, cursorPosition) + "\n" + messageBox.value.slice(cursorPosition);
        messageBox.selectionStart = messageBox.selectionEnd = cursorPosition + 1; // 將光標移到新行
    }
    // Enter 發送
    else if (event.key === "Enter" && !event.shiftKey) {
        event.preventDefault(); // 阻止默認行為
        document.getElementById("send-btn").click(); // 觸發發送按鈕
        
    }
});



    </script>
</body>
</html>
