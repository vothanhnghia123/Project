<?php
// Xay dung base URL phan trang
if ($filterType === 'danhmuc') {
    $basePageUrl = 'index.php?controller=Book&action=danhmuc&param=' . $filterId . '&';
} elseif ($filterType === 'theloai') {
    $basePageUrl = 'index.php?controller=Book&action=theloai&param=' . $filterId . '&';
} else {
    $basePageUrl = 'index.php?controller=Book&action=index&';
}
?>

<div class="sales" style="margin-top:20px;">
  <div class="container">
    <div class="row">

      <!-- SIDEBAR -->
      <div class="col-md-2 sidebar">
        <h5 style="font-weight:700; margin-bottom:12px;">SÁCH</h5>
        <ul class="menu-left">

          <li>
            <a href="index.php?controller=Book&action=index"
               class="<?= ($filterType === null) ? 'active-menu' : '' ?>">
              Tất cả sách
            </a>
          </li>

          <?php foreach ($categories as $dm):
            $dmId      = $dm['IDDanhMuc'];
            $isActive  = ($filterType === 'danhmuc' && $filterId == $dmId);

            $childActive = false;
            if ($filterType === 'theloai') {
                foreach ($dm['theloai'] as $tl) {
                    if ($tl['IDTheLoai'] == $filterId) { $childActive = true; break; }
                }
            }
            $showSub = ($isActive || $childActive) ? 'show' : '';
          ?>
          <li style="margin-top:8px;">
            <a href="index.php?controller=Book&action=danhmuc&param=<?= (int)$dmId ?>"
               class="<?= $isActive ? 'active-menu' : '' ?>"
               style="font-weight:600;">
              <?= htmlspecialchars($dm['TenDanhMuc']) ?>
            </a>

            <ul class="submenu collapse <?= $showSub ?>"
                id="dm<?= (int)$dmId ?>">
              <?php foreach ($dm['theloai'] as $tl): ?>
              <li>
                <a href="index.php?controller=Book&action=theloai&param=<?= (int)$tl['IDTheLoai'] ?>"
                   class="<?= ($filterType === 'theloai' && $filterId == $tl['IDTheLoai']) ? 'active-menu' : '' ?>">
                  <?= htmlspecialchars($tl['TenTheLoai']) ?>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </li>
          <?php endforeach; ?>

        </ul>
      </div><!-- /.sidebar -->

      <!-- NOI DUNG SAN PHAM -->
      <div class="col-md-10 content-right">

        <h4 style="margin-bottom:16px; font-weight:700; text-align:center;">
          <?= htmlspecialchars($title) ?>
        </h4>

        <div class="product-item page-sanpham">
          <?php if (!empty($books)): ?>
            <?php foreach ($books as $row): ?>
            <a class="item-sales"
               href="index.php?controller=Book&action=detail&param=<?= (int)$row['IDSach'] ?>">
              <img class="product-image"
                   src="public/images/sach/<?= htmlspecialchars($row['HinhAnh']) ?>"
                   alt="<?= htmlspecialchars($row['TenSach']) ?>">
              <div class="product-detail">
                <h6 class="product-title"><?= htmlspecialchars($row['TenSach']) ?></h6>
                <p class="price"><?= number_format($row['GiaBan'], 0, ',', '.') ?> đ</p>
              </div>
            </a>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="padding:30px 0; color:#888; grid-column:1/-1;">Không có sản phẩm nào.</p>
          <?php endif; ?>
        </div>

        <!-- PHAN TRANG -->
        <?php if ($totalPage > 1): ?>
        <div class="pagination text-center mt-4">

          <?php $range = 2; ?>

          <?php if ($currentPage > 1): ?>
            <a class="page-btn" href="<?= $basePageUrl ?>page=<?= $currentPage - 1 ?>">«</a>
          <?php endif; ?>

          <?php if ($currentPage > $range + 1): ?>
            <a class="page-btn" href="<?= $basePageUrl ?>page=1">1</a>
            <?php if ($currentPage > $range + 2): ?>
              <span class="page-btn">…</span>
            <?php endif; ?>
          <?php endif; ?>

          <?php for ($i = max(1, $currentPage - $range); $i <= min($totalPage, $currentPage + $range); $i++): ?>
            <?php if ($i === $currentPage): ?>
              <span class="page-btn active"><?= $i ?></span>
            <?php else: ?>
              <a class="page-btn" href="<?= $basePageUrl ?>page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if ($currentPage < $totalPage - $range): ?>
            <?php if ($currentPage < $totalPage - $range - 1): ?>
              <span class="page-btn">…</span>
            <?php endif; ?>
            <a class="page-btn" href="<?= $basePageUrl ?>page=<?= $totalPage ?>"><?= $totalPage ?></a>
          <?php endif; ?>

          <?php if ($currentPage < $totalPage): ?>
            <a class="page-btn" href="<?= $basePageUrl ?>page=<?= $currentPage + 1 ?>">»</a>
          <?php endif; ?>

        </div>
        <?php endif; ?>

      </div><!-- /.col-md-10 -->

    </div><!-- /.row -->

    <!-- SACH GOI Y -->
    <div class="sales" style="margin-top:20px;">
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

  </div><!-- /.container -->
</div>
