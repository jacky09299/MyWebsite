<?php
$host = 'localhost:3306';
$dbname = 'hgpqtbmy_test';
$user = 'hgpqtbmy_test';
$password = 'wryip951753J';

header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['page'])) {
            $page = $data['page'];

            // 查詢該頁面所有日期的預約數量
            $stmt = $pdo->prepare("SELECT date, COUNT(*) as count FROM reservations WHERE page = :page GROUP BY date");
            $stmt->execute(['page' => $page]);
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = [];
            foreach ($reservations as $reservation) {
                $result[$reservation['date']] = [
                    'count' => $reservation['count']
                ];
            }

            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'message' => '無效的請求。']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => '無法處理請求。']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '資料庫連線錯誤: ' . $e->getMessage()]);
}
?>
