<!-- Thong bao them gio thanh cong -->
<div id="msg-add">
  <i class="fa fa-check"></i> Đã thêm sản phẩm vào giỏ hàng!
</div>

<!-- THONG TIN SACH -->
<div class="container single-product" style="margin-top:50px; margin-bottom:50px;">
  <div class="row">

    <!-- Hinh anh -->
    <div class="col-md-5">
      <img class="product-img img-fluid"
           src="public/images/sach/<?= htmlspecialchars($book['HinhAnh'] ?? '') ?>"
           alt="<?= htmlspecialchars($book['TenSach'] ?? '') ?>"
           style="width:100%; border:1px solid #eee; border-radius:8px;">
    </div>

    <!-- Thong tin -->
    <div class="col-md-7 product-description">
      <h2 style="font-weight:bold; color:#333;">
        <?= htmlspecialchars($book['TenSach'] ?? '') ?>
      </h2>

      <h3 style="color:#d9534f; margin:20px 0;">
        Giá: <?= number_format((float)($book['GiaBan'] ?? 0), 0, ',', '.') ?> đ
      </h3>

      <ul class="list-unstyled info-book" style="line-height:2.2; font-size:16px;">
        <li>
          <i class="fa-solid fa-user-pen" style="width:25px;"></i>
          <b>Tác giả:</b> <?= htmlspecialchars($book['TenTacGia'] ?? '—') ?>
        </li>
        <li>
          <i class="fa-solid fa-tags" style="width:25px;"></i>
          <b>Thể loại:</b>
          <?php if (!empty($book['TenTheLoai'])): ?>
            <a href="index.php?controller=Book&action=theloai&param=<?= (int)($book['IDTheLoai'] ?? 0) ?>"
               style="color:#e53935; text-decoration:none;">
              <?= htmlspecialchars($book['TenTheLoai']) ?>
            </a>
          <?php else: ?>—<?php endif; ?>
        </li>
        <li>
          <i class="fa-solid fa-building-columns" style="width:25px;"></i>
          <b>Nhà xuất bản:</b> <?= htmlspecialchars($book['TenNXB'] ?? '—') ?>
        </li>
        <li>
          <i class="fa-solid fa-file-lines" style="width:25px;"></i>
          <b>Số trang:</b> <?= (int)($book['SoTrang'] ?? 0) ?> trang
        </li>
        <li>
          <i class="fa-solid fa-calendar-days" style="width:25px;"></i>
          <b>Năm xuất bản:</b> <?= htmlspecialchars($book['NamXB'] ?? '—') ?>
        </li>
        <li>
          <i class="fa-solid fa-boxes-stacked" style="width:25px;"></i>
          <b>Tình trạng:</b>
          <?php if ((int)($book['SoLuong'] ?? 0) > 0): ?>
            <span class="text-success">Còn hàng (<?= (int)$book['SoLuong'] ?>)</span>
          <?php else: ?>
            <span class="text-danger">Hết hàng</span>
          <?php endif; ?>
        </li>
      </ul>

      <!-- Mo ta -->
      <div style="margin-top:24px;">
        <h5 style="border-bottom:2px solid #eee; padding-bottom:8px; font-weight:bold; color:#555;">
          MÔ TẢ SÁCH
        </h5>
        <p style="line-height:1.8; color:#666; text-align:justify;">
          <?= nl2br(htmlspecialchars($book['MoTa'] ?? '')) ?>
        </p>
      </div>

      <!-- Nut them gio -->
      <div style="margin-top:28px;">
        <?php if ((int)($book['SoLuong'] ?? 0) > 0): ?>
        <button class="btn btn-danger btn-lg shadow-sm add-cart"
                data-id="<?= (int)$book['IDSach'] ?>"
                style="padding:10px 30px; font-weight:bold;">
          <i class="fa-solid fa-cart-shopping"></i> THÊM VÀO GIỎ HÀNG
        </button>
        <?php else: ?>
        <button class="btn btn-secondary btn-lg shadow-sm" disabled
                style="padding:10px 30px; font-weight:bold;">
          <i class="fa-solid fa-ban"></i> HẾT HÀNG
        </button>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<!-- DANH GIA -->
<div class="container single-product" style="margin-bottom:60px;">
  <div class="row">
    <div class="col-12">

      <div class="write-review-box" style="margin-top:30px;">
        <h4>Đánh giá sản phẩm</h4>

        <?php if (!isset($_SESSION['IDNguoiDung'])): ?>
          <div style="padding:20px; border:1px solid #ddd; border-radius:8px;">
            <p>
              Chỉ có thành viên mới có thể viết nhận xét. Vui lòng
              <a href="index.php?controller=User&action=login">đăng nhập</a>
              hoặc
              <a href="index.php?controller=User&action=register">đăng ký</a>.
            </p>
          </div>
        <?php else: ?>

          <!-- Tong hop sao -->
          <div class="rating-summary">

            <div class="rating-left" style="text-align:center; min-width:100px;">
              <h1 style="font-size:48px; margin:0; color:#f5a623;">
                <?= number_format((float)($rating['trungbinh'] ?? 0), 1) ?>/5
              </h1>
              <div style="color:#f5a623; font-size:20px;">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <?= $i <= round((float)($rating['trungbinh'] ?? 0))
                      ? '<i class="fa fa-star"></i>'
                      : '<i class="fa-regular fa-star"></i>' ?>
                <?php endfor; ?>
              </div>
              <p style="color:#888; margin-top:4px;">(<?= (int)($tong ?? 0) ?> đánh giá)</p>
            </div>

            <div class="rating-right" style="flex:1; min-width:200px;">
              <?php foreach ([5, 4, 3, 2, 1] as $s): ?>
              <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                <span style="min-width:38px; font-size:13px;"><?= $s ?> sao</span>
                <div style="flex:1; height:10px; background:#eee; border-radius:5px; overflow:hidden;">
                  <div style="width:<?= (int)($pt[$s] ?? 0) ?>%; height:100%; background:#f5a623;"></div>
                </div>
                <span style="min-width:36px; font-size:13px; text-align:right;">
                  <?= (int)($pt[$s] ?? 0) ?>%
                </span>
              </div>
              <?php endforeach; ?>
            </div>

            <div>
              <button id="btn-review" class="btn-review">
                <i class="fa fa-pen"></i> Viết đánh giá
              </button>
            </div>

          </div><!-- /.rating-summary -->

          <!-- Form gui danh gia (an mac dinh) -->
          <div id="review-form"
               style="display:none; margin-top:20px; border:1px solid #ddd; padding:20px; border-radius:8px;">
            <form action="index.php?controller=Book&action=review" method="POST">
              <input type="hidden" name="idsach" value="<?= (int)$book['IDSach'] ?>">

              <div style="margin-bottom:12px;">
                <?php foreach ([5, 4, 3, 2, 1] as $s): ?>
                <label style="margin-right:12px;">
                  <input type="radio" name="sosao"
                         value="<?= $s ?>" <?= $s === 5 ? 'checked' : '' ?>>
                  <?= str_repeat('⭐', $s) ?>
                </label><br>
                <?php endforeach; ?>
              </div>

              <textarea name="noidung" class="form-control" rows="4"
                        placeholder="Cảm nhận của bạn về cuốn sách này..."
                        required></textarea>

              <button type="submit" class="btn btn-danger" style="margin-top:10px;">
                Gửi đánh giá
              </button>
            </form>
          </div>

        <?php endif; ?>
      </div><!-- /.write-review-box -->

      <!-- Danh sach danh gia -->
      <div style="margin-top:40px;">
        <h4 style="font-weight:bold;">Nhận xét mới nhất</h4>

        <?php if (!empty($reviews)): ?>
          <?php foreach ($reviews as $dg): ?>
          <div class="review-item" style="border-bottom:1px solid #eee; padding:15px 0;">
            <b><?= htmlspecialchars($dg['HoTen'] ?? 'Ẩn danh') ?></b>
            <div style="color:#f5a623; margin:4px 0;">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <?= $i <= (int)($dg['SoSao'] ?? 0)
                    ? '<i class="fa fa-star"></i>'
                    : '<i class="fa-regular fa-star"></i>' ?>
              <?php endfor; ?>
            </div>
            <p style="margin-top:6px; color:#444;">
              <?= nl2br(htmlspecialchars($dg['NoiDung'] ?? '')) ?>
            </p>
            <small style="color:#aaa;">
              <?= !empty($dg['NgayDanhGia']) ? date('d/m/Y', strtotime($dg['NgayDanhGia'])) : '' ?>
            </small>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color:#888;">Chưa có đánh giá nào cho cuốn sách này.</p>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<script>
document.getElementById('btn-review')?.addEventListener('click', function () {
    const form = document.getElementById('review-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
});

// AJAX them vao gio
document.querySelector('.add-cart')?.addEventListener('click', function () {
    const id = this.dataset.id;
    fetch('index.php?controller=Book&action=addcart&id=' + id + '&so_luong=1')
        .then(res => res.text())
        .then(total => {
            console.log('Tong gio hang:', total);

            const badge = document.getElementById('cart-count');

            console.log('Badge:', badge);

            if (badge) {
                badge.innerText = total;
                badge.style.display = 'inline-block';
            }
        })
        .catch(err => console.error('Lỗi thêm giỏ hàng:', err));
});
</script>
