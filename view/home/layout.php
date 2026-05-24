<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore – Sách Hay Mỗi Ngày</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<?php
/**
 * layout.php — Layout dùng chung cho toàn bộ trang user (Home + Book).
 *
 * Controller PHẢI truyền vào scope:
 *   $viewFile   (string) — đường dẫn tuyệt đối đến view cần render
 *   $categories (array)  — danh mục có lồng 'theloai', dùng cho mega-menu header
 *
 * Nếu controller cũ chỉ truyền $danhmucs (flat), layout tự bổ sung theloai
 * để không bị lỗi (backward-compatible).
 */
if (!isset($categories)) {
    if (isset($danhmucs)) {
        // Bổ sung theloai cho mỗi danh mục
        require_once BASE_PATH . '/config/db.php';
        $_db = Database::getInstance();
        $categories = [];
        foreach ($danhmucs as $_dm) {
            $_s = $_db->prepare('SELECT * FROM theloai WHERE IDDanhMuc = ? ORDER BY IDTheLoai');
            $_s->execute([$_dm['IDDanhMuc']]);
            $_dm['theloai'] = $_s->fetchAll();
            $categories[] = $_dm;
        }
    } else {
        // Fallback: load thẳng từ DB
        require_once BASE_PATH . '/config/db.php';
        $_db = Database::getInstance();
        $_dms = $_db->query('SELECT * FROM danhmuc ORDER BY IDDanhMuc')->fetchAll();
        $categories = [];
        foreach ($_dms as $_dm) {
            $_s = $_db->prepare('SELECT * FROM theloai WHERE IDDanhMuc = ? ORDER BY IDTheLoai');
            $_s->execute([$_dm['IDDanhMuc']]);
            $_dm['theloai'] = $_s->fetchAll();
            $categories[] = $_dm;
        }
    }
}
?>

<?php require_once BASE_PATH . '/view/home/header.php'; ?>

<main>
    <div class="main">
        <?php
        if (isset($viewFile) && file_exists($viewFile)) {
            require $viewFile;
        } else {
            // Fallback trang chủ nếu không có $viewFile
            require_once BASE_PATH . '/view/home/index.php';
        }
        ?>
    </div>
</main>

<?php require_once BASE_PATH . '/view/home/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
