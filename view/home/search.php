<!-- KET QUA TIM KIEM -->
<div class="sales">
    <div class="container sales-product">
        <div class="box-white">

            <h3 class="title-sale">
                KẾT QUẢ TÌM KIẾM:
                "<?= htmlspecialchars($keyword) ?>"
                <small style="font-size:.8em; font-weight:normal;">
                    (<?= count($results ?? []) ?> sản phẩm)
                </small>
            </h3>

            <div class="product-item">
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $book): ?>
                    <a class="item-sales"
                       href="index.php?controller=Book&action=detail&param=<?= (int)$book['IDSach'] ?>">
                        <img class="product-image"
                             src="public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
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
                    <p style="padding:20px; grid-column:1/-1;">
                        Không tìm thấy sản phẩm phù hợp với từ khoá
                        "<strong><?= htmlspecialchars($keyword) ?></strong>".
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- SACH GOI Y -->
<div class="sales">
    <div class="container sales-product">
        <div class="box-white">
            <h3 class="title-sale">SÁCH GỢI Ý</h3>
            <div class="product-item">
                <?php foreach ($suggestedBooks as $book): ?>
                <a class="item-sales"
                   href="index.php?controller=Book&action=detail&param=<?= (int)$book['IDSach'] ?>">
                    <img class="product-image"
                         src="public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
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
