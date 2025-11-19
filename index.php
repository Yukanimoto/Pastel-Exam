<?php
require 'config.php';

$university = $_GET['university'] ?? '';
$subject    = $_GET['subject'] ?? '';
$year       = $_GET['year'] ?? '';

$query  = "SELECT * FROM exams WHERE 1=1";
$params = [];

if ($university !== '') {
    $query .= " AND university LIKE :university";
    $params[':university'] = '%' . $university . '%';
}

if ($subject !== '') {
    $query .= " AND subject LIKE :subject";
    $params[':subject'] = '%' . $subject . '%';
}

if ($year !== '') {
    $query .= " AND year = :year";
    $params[':year'] = (int)$year;
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kho đề thi đại học</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="container">

    <h1>Kho đề thi đại học</h1>

    <p><a href="upload.php">+ Thêm đề thi mới</a></p>

    <h2>Tìm kiếm</h2>
    <form method="get" action="">
        <label>Trường:</label>
        <input type="text" name="university" value="<?php echo htmlspecialchars($university); ?>">

        <label>Môn:</label>
        <input type="text" name="subject" value="<?php echo htmlspecialchars($subject); ?>">

        <label>Năm:</label>
        <input type="number" name="year" value="<?php echo htmlspecialchars($year); ?>">

        <button type="submit">Lọc</button>
    </form>

    <h2>Danh sách đề thi</h2>

    <?php if (count($exams) === 0): ?>
        <p>Không tìm thấy đề thi nào.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Trường</th>
                <th>Môn</th>
                <th>Năm</th>
                <th>Tải về</th>
            </tr>
<?php foreach ($exams as $exam): ?>
    <tr>
        <td>🔢 <?php echo $exam['id']; ?></td>
        <td>📘 <?php echo htmlspecialchars($exam['title']); ?></td>
        <td>🏫 <?php echo htmlspecialchars($exam['university']); ?></td>
        <td>📚 <?php echo htmlspecialchars($exam['subject']); ?></td>
        <td>📅 <?php echo htmlspecialchars($exam['year']); ?></td>
        <td>
            <a class="download-btn" href="download.php?id=<?php echo $exam['id']; ?>">⬇ Tải xuống</a>
        </td>
    </tr>
<?php endforeach; ?>
        </table>
    <?php endif; ?>

</div>
<?php include "footer.php"; ?>

</body>
</html>
