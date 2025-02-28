<?php
session_start();
require '../../config/config.php';

// 確保使用者已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: ../members/login.php");
    exit();
}

// 確保 uploads 資料夾存在
$uploadDir = '../../drive/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// 處理檔案上傳
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileName = basename($_FILES['file']['name']);
    $targetFile = $uploadDir . $fileName;
    $fileSize = $_FILES['file']['size'];
    $userId = $_SESSION['user_id'];

    // 檢查檔案是否已存在
    if (file_exists($targetFile)) {
        $message = "File already exists.";
    } else {
        // 將檔案移至 uploads 資料夾
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            // 儲存檔案資訊到資料庫
            $stmt = $pdo->prepare("INSERT INTO files (user_id, file_name, file_path, file_size) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $fileName, $targetFile, $fileSize]);
            $message = "File uploaded successfully.";
        } else {
            $message = "File upload failed.";
        }
    }
}

// 從資料庫獲取使用者的檔案
$stmt = $pdo->prepare("SELECT * FROM files WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
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

    <!-- 上傳檔案表單 -->
    <form action="drive.php" method="POST" enctype="multipart/form-data">
        <label for="file">Upload File:</label>
        <input type="file" name="file" id="file" required>
        <button type="submit">Upload</button>
    </form>

    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <h2>Your Files</h2>
    <ul>
        <?php foreach ($files as $file): ?>
            <li>
                <a href="<?php echo htmlspecialchars($file['file_path']); ?>" download>
                    <?php echo htmlspecialchars($file['file_name']); ?> 
                </a> 
                (<?php echo round($file['file_size'] / 1024, 2); ?> KB)
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>