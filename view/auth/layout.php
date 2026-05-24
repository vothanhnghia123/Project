<?php
/**
 * view/auth/layout.php
 * Layout dùng chung cho tất cả trang Auth (login, register, profile)
 * Biến $viewFile phải được set trước khi require layout này.
 * Biến $categories dùng cho mega-menu trong header.php
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore – Sách Hay Mỗi Ngày</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<?php require_once BASE_PATH . '/view/home/header.php'; ?>

<main>
    <div class="main">
        <?php require $viewFile; ?>
    </div>
</main>

<?php require_once BASE_PATH . '/view/home/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
