<?php
// ============================================================
//  View\Book\List — Danh sách sách (sidebar + phân trang)
//  Biến từ controller/Book.php:
//    $books       — sách trang hiện tại
//    $categories  — danh mục CÓ 'theloai' lồng trong (dùng sidebar)
//    $title       — tiêu đề trang
//    $currentPage — trang hiện tại
//    $totalPage   — tổng số trang
//    $filterType  — 'danhmuc' | 'theloai' | null
//    $filterId    — ID đang lọc
// ============================================================

$basePageUrl = BASE_URL;
if ($filterType === 'danhmuc')     $basePageUrl .= '/book/danhmuc/' . $filterId;
elseif ($filterType === 'theloai') $basePageUrl .= '/book/theloai/' . $filterId;
else                               $basePageUrl .= '/book';
?>

<div class="sales" style="margin-top:20px;">
  <div class="container">
    <div class="row">

      <!-- ══════════ SIDEBAR ══════════ -->
      <div class="col-md-2 sidebar">
        <h5 style="font-weight:700; margin-bottom:12px;">SÁCH</h5>
        <ul class="menu-left" style="list-style:none; padding:0;">

          <li>
            <a href="<?= BASE_URL ?>/book"
               class="<?= ($filterType === null) ? 'active-menu' : '' ?>">
              Tất cả sách
            </a>
          </li>

          <?php foreach ($categories as $dm):
            $dmId       = $dm['IDDanhMuc'];
            $isActive   = ($filterType === 'danhmuc' && $filterId == $dmId);

            // Kiểm tra có thể loại con nào đang được chọn không
            $childActive = false;
            if ($filterType === 'theloai') {
                foreach ($dm['theloai'] as $tl) {
                    if ($tl['IDTheLoai'] == $filterId) { $childActive = true; break; }
                }
            }
            $showSub = ($isActive || $childActive) ? 'show' : '';
          ?>
          <li style="margin-top:8px;">
            <a href="<?= BASE_URL ?>/book/danhmuc/<?= $dmId ?>"
               class="<?= $isActive ? 'active-menu' : '' ?>"
               style="font-weight:600;">
              <?= htmlspecialchars($dm['TenDanhMuc']) ?>
            </a>

            <ul class="submenu collapse <?= $showSub ?>"
                id="dm<?= $dmId ?>"
                style="list-style:none; padding-left:14px; margin-top:4px;">
              <?php foreach ($dm['theloai'] as $tl): ?>
              <li>
                <a href="<?= BASE_URL ?>/book/theloai/<?= $tl['IDTheLoai'] ?>"
                   class="<?= ($filterType === 'theloai' && $filterId == $tl['IDTheLoai']) ? 'active-menu' : '' ?>"
                   style="font-size:13px; color:#555;">
                  <?= htmlspecialchars($tl['TenTheLoai']) ?>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </li>
          <?php endforeach; ?>

        </ul>
      </div><!-- /.sidebar -->

      <!-- ══════════ NỘI DUNG SẢN PHẨM ══════════ -->
      <div class="col-md-10 content-right">

        <h4 style="margin-bottom:16px; font-weight:700;">
          <?= htmlspecialchars($title) ?>
        </h4>

        <div class="product-item page-sanpham">
          <?php if (!empty($books)): ?>
            <?php foreach ($books as $row): ?>
            <a class="item-sales"
               href="<?= BASE_URL ?>/book/detail/<?= (int)$row['IDSach'] ?>">
              <img class="product-image"
                   src="<?= BASE_URL ?>/public/images/sach/<?= htmlspecialchars($row['HinhAnh']) ?>"
                   alt="<?= htmlspecialchars($row['TenSach']) ?>">
              <div class="product-detail">
                <h6 class="product-title"><?= htmlspecialchars($row['TenSach']) ?></h6>
                <p class="price"><?= number_format($row['GiaBan'], 0, ',', '.') ?> đ</p>
              </div>
            </a>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="padding:30px 0; color:#888;">Không có sản phẩm nào.</p>
          <?php endif; ?>
        </div>

        <!-- ══════ PHÂN TRANG ══════ -->
        <?php if ($totalPage > 1): ?>
        <div class="pagination text-center mt-4"
             style="display:flex; justify-content:center; gap:6px; flex-wrap:wrap;">

          <?php $range = 2; ?>

          <?php if ($currentPage > 1): ?>
            <a class="page-btn" href="<?= $basePageUrl ?>?page=<?= $currentPage - 1 ?>">«</a>
          <?php endif; ?>

          <?php if ($currentPage > $range + 1): ?>
            <a class="page-btn" href="<?= $basePageUrl ?>?page=1">1</a>
            <?php if ($currentPage > $range + 2): ?>
              <span class="page-btn">…</span>
            <?php endif; ?>
          <?php endif; ?>

          <?php for ($i = max(1, $currentPage - $range); $i <= min($totalPage, $currentPage + $range); $i++): ?>
            <?php if ($i === $currentPage): ?>
              <span class="page-btn active"><?= $i ?></span>
            <?php else: ?>
              <a class="page-btn" href="<?= $basePageUrl ?>?page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if ($currentPage < $totalPage - $range): ?>
            <?php if ($currentPage < $totalPage - $range - 1): ?>
              <span class="page-btn">…</span>
            <?php endif; ?>
            <a class="page-btn" href="<?= $basePageUrl ?>?page=<?= $totalPage ?>"><?= $totalPage ?></a>
          <?php endif; ?>

          <?php if ($currentPage < $totalPage): ?>
            <a class="page-btn" href="<?= $basePageUrl ?>?page=<?= $currentPage + 1 ?>">»</a>
          <?php endif; ?>

        </div>
        <?php endif; ?>

      </div><!-- /.col-md-10 -->

    </div><!-- /.row -->
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
  </div><!-- /.container -->
</div>
