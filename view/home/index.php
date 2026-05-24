<?php
/**
 * view/home/index.php
 * Nội dung trang chủ: Sách Mới + Văn Học + Thiếu Nhi + Sách Gợi Ý
 * Biến được truyền từ controller/Home.php::index()
 *   $newBooks        – mảng sách mới
 *   $vanhocBooks     – mảng sách Văn học
 *   $vanhocDMId      – IDDanhMuc của Văn học
 *   $thieunhiBooks   – mảng sách Thiếu nhi
 *   $thieunhiDMId    – IDDanhMuc của Thiếu nhi
 *   $suggestedBooks  – mảng sách gợi ý ngẫu nhiên
 */
?>

<!-- ======================================================
     SECTION 1: SÁCH MỚI (Carousel)
====================================================== -->
<div class="book-carousel-section">
    <div class="container">
        <div class="book-carousel-box">
            <h3 class="book-carousel-title">SÁCH MỚI</h3>

            <div class="book-carousel-wrapper">
                <button class="book-carousel-btn btn-prev" onclick="moveSlide(-1)">&#10094;</button>

                <div class="book-carousel-container" id="bookSlider">
                    <?php foreach ($newBooks as $book): ?>
                    <a class="book-carousel-item"
                       href="<?= BASE_URL ?>/book/detail/<?= $book['IDSach'] ?>">
                        <div class="book-card-img">
                            <img src="<?= BASE_URL ?>/public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
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

<!-- ======================================================
     SECTION 2: VĂN HỌC
====================================================== -->
<div class="sales">
    <div class="container sales-product">
        <div class="box-white">
            <h3 class="title-sale">VĂN HỌC</h3>

            <div class="product-item">
                <?php foreach ($vanhocBooks as $book): ?>
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

            <?php if ($vanhocDMId): ?>
            <div class="view-more">
                <a href="<?= BASE_URL ?>/book/danhmuc/<?= $vanhocDMId ?>"
                   class="btn-view-more">Xem thêm</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ======================================================
     SECTION 3: THIẾU NHI
====================================================== -->
<div class="sales">
    <div class="container sales-product">
        <div class="box-white">
            <h3 class="title-sale">THIẾU NHI</h3>

            <div class="product-item">
                <?php foreach ($thieunhiBooks as $book): ?>
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

            <?php if ($thieunhiDMId): ?>
            <div class="view-more">
                <a href="<?= BASE_URL ?>/book/danhmuc/<?= $thieunhiDMId ?>"
                   class="btn-view-more">Xem thêm</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ======================================================
     SECTION 4: SÁCH GỢI Ý (ngẫu nhiên)
====================================================== -->
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
