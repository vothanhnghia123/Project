<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm: "<?= htmlspecialchars($keyword) ?>" – BookStore</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<?php require_once BASE_PATH . '/view/home/header.php'; ?>

<!-- ===== KẾT QUẢ TÌM KIẾM ===== -->
<div class="sales">
    <div class="container sales-product">
        <div class="box-white">

            <h3 class="title-sale">
                KẾT QUẢ TÌM KIẾM:
                "<?= htmlspecialchars($keyword) ?>"
                <small style="font-size:.8em;font-weight:normal;">
                    (<?= count($results) ?> sản phẩm)
                </small>
            </h3>

            <div class="product-item">

                <?php if (count($results) > 0): ?>

                    <?php foreach ($results as $book): ?>
                    <a class="item-sales"
                       href="<?= BASE_URL ?>/book/detail/<?= $book['IDSach'] ?>">
                        <img class="product-image"
                             src="<?= BASE_URL ?>/public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
                             alt="<?= htmlspecialchars($book['TenSach']) ?>">
                        <div class="product-detail">
                            <h4 class="product-title"><?= htmlspecialchars($book['TenSach']) ?></h4>
                            <p class="price">
                                <?= number_format($book['GiaBan'], 0, ',', '.') ?> đ
                            </p>
                        </div>
                    </a>
                    <?php endforeach; ?>

                <?php else: ?>
                    <p style="padding:20px;">
                        Không tìm thấy sản phẩm phù hợp với từ khoá
                        "<strong><?= htmlspecialchars($keyword) ?></strong>".
                    </p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- ===== SÁCH GỢI Ý (bên dưới trang tìm kiếm) ===== -->
<div class="sales">
    <div class="container sales-product">
        <div class="box-white">
            <h3 class="title-sale">SÁCH GỢI Ý</h3>
            <div class="product-item">
                <?php foreach ($suggestedBooks as $book): ?>
                <a class="item-sales"
                   href="<?= BASE_URL ?>/book/detail/<?= $book['IDSach'] ?>">
                    <img class="product-image"
                         src="<?= BASE_URL ?>/public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
                         alt="<?= htmlspecialchars($book['TenSach']) ?>">
                    <div class="product-detail">
                        <h4 class="product-title"><?= htmlspecialchars($book['TenSach']) ?></h4>
                        <p class="price">
                            <?= number_format($book['GiaBan'], 0, ',', '.') ?> đ
                        </p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/view/home/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
