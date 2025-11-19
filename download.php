<?php
require 'config.php';

if (!isset($_GET['id'])) {
    die("
    <link rel='stylesheet' href='style.css'>
    <div class='container'>
        <h1>Lỗi tải file</h1>
        <p>Thiếu ID đề thi.</p>
        <p><a href='index.php'>← Quay lại</a></p>
    </div>");
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM exams WHERE id = :id");
$stmt->execute([':id' => $id]);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    die("
    <link rel='stylesheet' href='style.css'>
    <div class='container'>
        <h1>Lỗi tải file</h1>
        <p>Không tìm thấy đề thi.</p>
        <p><a href='index.php'>← Quay lại</a></p>
    </div>");
}

$filePath = $exam['file_path'];

if (!file_exists($filePath)) {
    die("
    <link rel='stylesheet' href='style.css'>
    <div class='container'>
        <h1>Lỗi tải file</h1>
        <p>File không tồn tại trên server.</p>
        <p><a href='index.php'>← Quay lại</a></p>
    </div>");
}

$filename = basename($filePath);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

readfile($filePath);
exit;
?>
