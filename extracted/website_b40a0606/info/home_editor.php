<?php
include '../common/navbar.php';
// 取得當前資料夾中的所有 txt 檔案
$files = glob("*.txt");

// 預設選擇的檔案為第一個檔案
$filePath = isset($_POST['file']) && in_array($_POST['file'], $files) ? $_POST['file'] : (count($files) > 0 ? $files[0] : '');

// 儲存後檔案名稱
$storedFile = '';

// 處理表單提交（儲存文件）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content']) && isset($_POST['file'])) {
    $filePath = $_POST['file']; // 取得使用者選擇的檔案
    $content = $_POST['content']; // 取得表單內容
    
    // 確保選擇的檔案存在，並儲存內容
    if (in_array($filePath, $files)) {
        file_put_contents($filePath, $content); // 儲存至選擇的 txt 檔案
        $storedFile = $filePath; // 儲存檔案名稱
    }
}

// 讀取選擇的檔案內容
if ($filePath && file_exists($filePath)) {
    $content = file_get_contents($filePath);
} else {
    $content = ''; // 若檔案不存在或未選擇檔案，給個空白的內容
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯網站介紹</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo filemtime('../css/style.css'); ?>">
</head>
<body>

    <h1>編輯網站介紹</h1>

    <!-- 表單來選擇要編輯的檔案 -->
    <form method="POST">
        <label for="file">選擇要編輯的檔案：</label>
        <select name="file" id="file" onchange="this.form.submit()">
            <option value="">-- 請選擇 --</option>
            <?php foreach ($files as $file): ?>
                <option value="<?php echo $file; ?>" <?php echo ($filePath === $file) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($file); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($filePath): ?>
        <!-- 顯示編輯表單 -->
        <form method="POST">
            <input type="hidden" name="file" value="<?php echo htmlspecialchars($filePath); ?>"> <!-- 保留檔案選擇 -->
            <label for="content">請修改檔案內容：</label>
            <textarea name="content" id="content"><?php echo htmlspecialchars($content); ?></textarea>
            <br>
            <button type="submit">儲存更改</button>
        </form>
    <?php endif; ?>

    <!-- 顯示儲存的檔案名稱 -->
    <?php if ($storedFile): ?>
        <div class="stored-file">已儲存檔案</div>
    <?php endif; ?>

</body>
</html>
