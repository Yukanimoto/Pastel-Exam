<?php
$host = "localhost";
$dbname = "exam_db";
$username = "root"; // đổi nếu bạn đặt khác
$password = "";     // XAMPP mặc định rỗng

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối database thất bại: " . $e->getMessage());
}
?>
