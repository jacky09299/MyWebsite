<?php
session_start();  // 啟動會話

// 清除所有 session 變數
$_SESSION = [];

// 取得當前會話的 session ID
$session_file = session_save_path() . '/sess_' . session_id();

// 刪除對應的會話文件
if (file_exists($session_file)) {
    unlink($session_file);  // 刪除會話文件
}

// 如果需要刪除 session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 銷毀 session
session_destroy();

// 跳轉回首頁或登入頁面
header("Location: ../../index.php");
exit();
?>