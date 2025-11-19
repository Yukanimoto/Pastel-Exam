<?php
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title      = $_POST['title'] ?? '';
    $university = $_POST['university'] ?? '';
    $subject    = $_POST['subject'] ?? '';
    $year       = (int)($_POST['year'] ?? 0);

    if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
        $message = "Lỗi khi upload file.";
    } else {
        $allowedExt = ['pdf', 'doc', 'docx'];
        $fileName   = $_FILES['file']['name'];
        $tmpName    = $_FILES['file']['tmp_name'];
        $ext        = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            $message = "Chỉ cho phép file PDF, DOC, DOCX.";
        } else {
            $newName  = time() . "_" . uniqid() . "." . $ext;
            $destPath = "uploads/" . $newName;

            if (!is_dir("uploads")) {
                mkdir("uploads", 0777, true);
            }

            if (move_uploaded_file($tmpName, $destPath)) {
                $stmt = $pdo->prepare("INSERT INTO exams (title, university, subject, year, file_path) 
                                       VALUES (:title, :university, :subject, :year, :file_path)");
                $stmt->execute([
                    ':title'      => $title,
                    ':university' => $university,
                    ':subject'    => $subject,
                    ':year'       => $year,
                    ':file_path'  => $destPath
                ]);

                $message = "🎉 Upload đề thi thành công!";
            } else {
                $message = "Không thể lưu file lên server.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Upload đề thi đại học</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="container">

    <h1>Upload đề thi đại học</h1>

    <?php if ($message): ?>
        <p style="padding: 12px; background: #ffe0f0; border-radius: 10px; color: #d63384;">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">

        <label>Tiêu đề đề thi:</label>
        <input type="text" name="title" required>

        <label>Trường đại học:</label>
        <input type="text" name="university" placeholder="VD: ĐH Bách Khoa Hà Nội" required>

        <label>Môn:</label>
        <input type="text" name="subject" placeholder="Toán, Lý, Hóa..." required>

        <label>Năm:</label>
        <input type="number" name="year" min="1990" max="2100" required>

        <label>File đề thi (PDF/DOC/DOCX):</label>
        <input type="file" name="file" required>

        <button type="submit">Upload</button>
    </form>

    <p><a href="index.php">← Về trang danh sách đề thi</a></p>

</div>
<?php include "footer.php"; ?>

</body>
</html>
