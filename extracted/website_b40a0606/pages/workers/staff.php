<?php
session_start();
include '../../common/navbar.php';  // 引入共用的導覽列

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

// Check if the user has the 'admin' or 'staff' role
if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
    header("Location: ../../index.php");
    exit();
}

// Database credentials
$host = 'localhost:3306';
$dbname = 'hgpqtbmy_test';
$user = 'hgpqtbmy_test';
$password = 'wryip951753J';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch reservation data
    $stmt = $pdo->query("SELECT id, page, date, username, created_at FROM reservations ORDER BY date DESC");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log the error and show a user-friendly message
    error_log('Database connection error: ' . $e->getMessage());
    die("Database connection error. Please try again later.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reservation List</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
    <link rel="stylesheet" href="../../css/snowflake.css?v=<?php echo filemtime('../../css/snowflake.css'); ?>">
    <script src="../../js/snowflake.js?v=<?php echo filemtime('../../js/snowflake.js'); ?>"></script>
</head>
<body>
    <h1>Reservation Orders (Admin/Staff Only)</h1>
    
    <?php if (!empty($reservations)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Page</th>
                <th>Date</th>
                <th>Username</th>
                <th>Created At</th>
            </tr>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['username']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['page']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['date']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No reservations found.</p>
    <?php endif; ?>
    <div class="snowflake-container"></div>
</body>
</html>
