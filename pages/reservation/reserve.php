<?php
session_start();

// 檢查使用者是否已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

// 資料庫憑證
$host = 'localhost:3306';
$dbname = 'hgpqtbmy_test';
$user = 'hgpqtbmy_test';
$password = 'wryip951753J';

header('Content-Type: application/json');

try {
    // 建立資料庫連線
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // 確認 action 和必要的欄位是否存在
        if (isset($data['action'], $data['page'], $data['date']) && $data['action'] === 'reserve') {
            $page = htmlspecialchars($data['page']);
            $date = htmlspecialchars($data['date']);
            $username = htmlspecialchars($_SESSION['username']); // 使用登入的用戶名稱

            // 查詢現有預約數量
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE date = :date AND page = :page");
            $stmt->execute(['date' => $date, 'page' => $page]);
            $reservationCount = $stmt->fetchColumn();

            // 檢查預約上限
            if ($reservationCount >= 3) {
                echo json_encode(['success' => false, 'message' => '此日期預約已達上限。']);
            } else {
                // 插入預約並包含使用者名稱
                $stmt = $pdo->prepare("INSERT INTO reservations (page, date, username) VALUES (:page, :date, :username)");
                $stmt->execute(['page' => $page, 'date' => $date, 'username' => $username]);
                echo json_encode(['success' => true, 'message' => '預約成功！']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => '無效的請求。']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => '無法處理請求。']);
    }
} catch (PDOException $e) {
    // 記錄錯誤並回傳通用錯誤訊息
    error_log('Database connection error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '資料庫連線錯誤，請稍後再試。']);
}
?>
