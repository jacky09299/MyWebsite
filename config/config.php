<?php
$host = 'localhost:3306';
$dbname = 'hgpqtbmy_test';
$user = 'hgpqtbmy_test';
$password = 'wryip951753J';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>