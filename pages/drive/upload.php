<?php
session_start();
require '../../config/config.php';

// 確保使用者已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: ../members/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$uploadDir = '../../protected_uploads/' . $userId . '/';

// 檢查並創建使用者資料夾
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$message = ""; // 用於顯示訊息

// 處理檔案上傳
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
    $files = $_FILES['files'];

    // 遍歷每個上傳的檔案
    foreach ($files['name'] as $index => $originalName) {
        $targetDir = $uploadDir . basename($originalName); // 保證存放的檔案路徑
        $hashedName = uniqid() . "_" . md5($originalName) . "." . pathinfo($originalName, PATHINFO_EXTENSION);
        
        // 儲存檔案到指定目錄
        if (move_uploaded_file($files['tmp_name'][$index], $targetDir)) {
            // 儲存檔案資訊到資料庫
            $stmt = $pdo->prepare("INSERT INTO files (user_id, original_name, hashed_name, file_size, path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $originalName, $hashedName, $files['size'][$index], $targetDir]);

            $message = "File uploaded successfully!";
        } else {
            $message = "File upload failed for $originalName. " . error_get_last()['message'];
        }
    }

    // 使用 PRG 模式重定向，避免重複提交表單
    header("Location: upload.php?message=" . urlencode($message));
    exit();
}

// 從資料庫檢索檔案列表
$stmt = $pdo->prepare("SELECT * FROM files WHERE user_id = ?");
$stmt->execute([$userId]);
$files = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Drive</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
</head>
<body>
    <?php include '../../common/navbar.php'; ?>
    <h1>Cloud Drive</h1>

    <!-- 顯示訊息 -->
    <?php if (isset($_GET['message'])) echo "<p>" . htmlspecialchars($_GET['message']) . "</p>"; ?>

    <!-- 上傳檔案表單 -->
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="files">Upload Files (or Folder):</label>
        <input type="file" name="files[]" id="files" multiple webkitdirectory required>
        <button type="submit">Upload</button>
    </form>

    <!-- 切換模式按鈕 -->
    <button id="toggleModeButton" onclick="toggleMode()">Switch to Open Mode</button>

    <!-- 檔案列表 -->
    <h2>Your Files:</h2>
    <ul id="fileList">
        <?php foreach ($files as $file): ?>
            <li>
                <a href="#" class="fileLink" data-path="<?php echo htmlspecialchars($file['path']); ?>" data-original-name="<?php echo htmlspecialchars($file['original_name']); ?>" data-extension="<?php echo pathinfo($file['original_name'], PATHINFO_EXTENSION); ?>">
                    <?php echo htmlspecialchars($file['original_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <script>
        let currentMode = 'download'; // 初始為下載模式

        // 切換模式
        function toggleMode() {
            if (currentMode === 'download') {
                currentMode = 'open';
                document.getElementById('toggleModeButton').textContent = "Switch to Download Mode";
            } else {
                currentMode = 'download';
                document.getElementById('toggleModeButton').textContent = "Switch to Open Mode";
            }
        }

        // 處理檔案點擊事件
        // 處理檔案點擊事件
document.getElementById('fileList').addEventListener('click', function(event) {
    if (event.target && event.target.matches('.fileLink')) {
        event.preventDefault();

        const filePath = event.target.getAttribute('data-path');
        const originalName = event.target.getAttribute('data-original-name');
        const extension = event.target.getAttribute('data-extension').toLowerCase();

        if (currentMode === 'open') {
            // 開啟模式，根據檔案類型選擇開啟方式
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(extension)) {
                // 圖片檔案
                window.open(filePath, '_blank');
            } else if (['mp3', 'wav', 'ogg'].includes(extension)) {
                // 音訊檔案
                window.open(filePath, '_blank');
            } else if (['mp4', 'mov', 'avi'].includes(extension)) {
                // 影片檔案
                window.open(filePath, '_blank');
            } else if (extension === 'txt') {
                // 文字檔案
                window.open(filePath, '_blank');
            } else if (extension === 'pdf') {
                // PDF 檔案
                window.open(filePath, '_blank');
            } else {
                // 未支援的檔案類型，提示錯誤
                alert('This file type cannot be opened directly in the browser.');
            }
        } else if (currentMode === 'download') {
            // 下載模式，下載檔案
            const link = document.createElement('a');
            link.href = filePath;
            link.download = originalName; // 設定下載檔案的原始名稱
            link.click();
        }
    }
});
    </script>
</body>
</html>