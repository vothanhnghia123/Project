<div class="main">

<!-- SECTION 1: SACH MOI (Carousel) -->
<div class="book-carousel-section">
    <div class="container">
        <div class="book-carousel-box">
            <h3 class="book-carousel-title">SÁCH MỚI</h3>

            <div class="book-carousel-wrapper">
                <button class="book-carousel-btn btn-prev" onclick="moveSlide(-1)">&#10094;</button>

                <div class="book-carousel-container" id="bookSlider">
                    <?php foreach ($newBooks as $book): ?>
                    <a class="book-carousel-item"
                       href="index.php?controller=Book&action=detail&param=<?= (int)$book['IDSach'] ?>">
                        <div class="book-card-img">
                            <img src="public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
                                 alt="<?= htmlspecialchars($book['TenSach']) ?>">
                        </div>
                        <div class="book-card-info">
                            <h4 class="book-title"><?= htmlspecialchars($book['TenSach']) ?></h4>
                            <p class="book-price">
                                <?= number_format($book['GiaBan'], 0, ',', '.') ?> đ
                            </p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

                <button class="book-carousel-btn btn-next" onclick="moveSlide(1)">&#10095;</button>
            </div>
        </div>
    </div>
</div>

<script>
function moveSlide(direction) {
    const slider = document.getElementById('bookSlider');
    slider.scrollBy({ left: direction * slider.clientWidth * 0.8, behavior: 'smooth' });
}
</script>

<!-- SECTION 2: VAN HOC -->
<div class="sales">
    <div class="container sales-product">
        <div class="box-white">
            <h3 class="title-sale">VĂN HỌC</h3>

            <div class="product-item">
                <?php foreach ($vanhocBooks as $book): ?>
                <a class="item-sales"
                   href="index.php?controller=Book&action=detail&param=<?= (int)$book['IDSach'] ?>">
                    <img class="product-image"
                         src="public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
                         alt="<?= htmlspecialchars($book['TenSach']) ?>">
                    <div class="product-detail">
                        <h4 class="product-title"><?= htmlspecialchars($book['TenSach']) ?></h4>
                        <p class="price"><?= number_format($book['GiaBan'], 0, ',', '.') ?> đ</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <?php if ($vanhocDMId): ?>
            <div class="view-more">
                <a href="index.php?controller=Book&action=danhmuc&param=<?= $vanhocDMId ?>"
                   class="btn-view-more">Xem thêm</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- SECTION 3: THIEU NHI -->
<div class="sales">
    <div class="container sales-product">
        <div class="box-white">
            <h3 class="title-sale">THIẾU NHI</h3>

            <div class="product-item">
                <?php foreach ($thieunhiBooks as $book): ?>
                <a class="item-sales"
                   href="index.php?controller=Book&action=detail&param=<?= (int)$book['IDSach'] ?>">
                    <img class="product-image"
                         src="public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
                         alt="<?= htmlspecialchars($book['TenSach']) ?>">
                    <div class="product-detail">
                        <h4 class="product-title"><?= htmlspecialchars($book['TenSach']) ?></h4>
                        <p class="price"><?= number_format($book['GiaBan'], 0, ',', '.') ?> đ</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <?php if ($thieunhiDMId): ?>
            <div class="view-more">
                <a href="index.php?controller=Book&action=danhmuc&param=<?= $thieunhiDMId ?>"
                   class="btn-view-more">Xem thêm</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- SECTION 4: SACH GOI Y -->
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
                        <p class="price"><?= number_format($book['GiaBan'], 0, ',', '.') ?> đ</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

</div><!-- /.main -->
