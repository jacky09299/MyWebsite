<?php
require '../../config/config.php';

// 確保使用者已登入
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../members/login.php");
    exit();
}

// 確保檔案 ID 存在
if (isset($_GET['file_id'])) {
    $fileId = $_GET['file_id'];

    // 從資料庫檢索檔案資料
    $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
    $stmt->execute([$fileId]);
    $file = $stmt->fetch();

    if ($file) {
        // 檢查檔案是否存在
        $filePath = '../../protected_uploads/' . $_SESSION['user_id'] . '/' . $file['hashed_name'];
        if (file_exists($filePath)) {
            // 強制下載
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
            readfile($filePath);
            exit();
        } else {
            echo "File not found.";
        }
    } else {
        echo "File does not exist.";
    }
} else {
    echo "No file ID specified.";
}