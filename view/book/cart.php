<?php
// ============================================================
//  View\Book\Cart — Trang giỏ hàng + đặt hàng
//  Biến có sẵn:
//    $cartItems      — array [{vitri, IDSach, TenSach, HinhAnh, GiaBan, SoLuong, ThanhTien}]
//    $tongCong       — tổng tiền toàn bộ giỏ
//    $thieuThongTin  — bool: user chưa có SĐT / địa chỉ
// ============================================================
$pageTitle = 'Giỏ hàng';
?>

<!-- Thông báo đặt hàng thành công -->
<?php if (isset($_GET['success'])): ?>
<div id="success-box" style="
  position:fixed; top:100px; right:30px; z-index:9999;
  background:#28a745; color:#fff; padding:18px 28px;
  border-radius:10px; font-size:18px; font-weight:600;
  box-shadow:0 4px 16px rgba(0,0,0,.2);">
  🎉 Đặt hàng thành công!
</div>
<script>
  setTimeout(function () {
    const box = document.getElementById('success-box');
    if (box) { box.style.opacity = '0'; box.style.transition = '0.5s'; setTimeout(() => box.remove(), 600); }
  }, 3000);
</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger text-center" style="margin:20px;">
  ⚠ Đặt hàng thất bại. Vui lòng kiểm tra lại giỏ hàng hoặc thử lại.
</div>
<?php endif; ?>

<div class="cart-container" style="display:flex; gap:24px; max-width:1200px; margin:40px auto; padding:0 16px; flex-wrap:wrap;">

  <!-- ══════════════════════════════════
       CỘT TRÁI — Danh sách sản phẩm
  ══════════════════════════════════ -->
  <div class="cart-left" style="flex:1; min-width:300px;">

    <div class="cart-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
      <h2 style="margin:0; font-size:22px; font-weight:700;">GIỎ HÀNG CỦA BẠN</h2>
      <a href="<?php echo BASE_URL; ?>/book/clearcart"
         class="btn-delete-all"
         style="color:#e74c3c; font-size:14px; text-decoration:none;"
         onclick="return confirm('Xóa toàn bộ giỏ hàng?')">
        Xóa tất cả
      </a>
    </div>

    <!-- Check-all -->
    <div style="margin-bottom:12px;">
      <input type="checkbox" id="check-all" checked>
      <label for="check-all" style="cursor:pointer; user-select:none;">Chọn tất cả</label>
    </div>

    <?php if (empty($cartItems)): ?>
      <div style="padding:24px; background:#fffbe6; border:1px solid #ffe58f; border-radius:8px; color:#888;">
        Giỏ hàng của bạn đang trống.
      </div>
    <?php else: ?>
      <?php foreach ($cartItems as $item): ?>
      <div class="cart-item" style="
        display:flex; align-items:center; gap:14px;
        padding:14px 0; border-bottom:1px solid #eee;">

        <input type="checkbox"
               class="check-item"
               data-price="<?php echo (int)$item['ThanhTien']; ?>"
               checked>

        <img src="<?php echo BASE_URL; ?>/public/images/sach/<?php echo htmlspecialchars($item['HinhAnh']); ?>"
             alt="<?php echo htmlspecialchars($item['TenSach']); ?>"
             class="cart-img"
             style="width:68px; height:90px; object-fit:cover; border-radius:4px; border:1px solid #eee;">

        <div class="cart-info" style="flex:1;">
          <h4 class="book-name" style="font-size:15px; margin:0 0 6px;">
            <?php echo htmlspecialchars($item['TenSach']); ?>
          </h4>
          <p class="cart-price" style="color:#e74c3c; font-weight:600; margin:0;">
            <?php echo number_format($item['GiaBan'], 0, ',', '.'); ?> đ
          </p>
        </div>

        <!-- Nút tăng / giảm -->
        <div class="cart-qty" style="display:flex; align-items:center; gap:6px;">
          <a href="<?php echo BASE_URL; ?>/book/qty?vi=<?php echo $item['vitri']; ?>&action=giam"
             class="btn-qty"
             style="display:inline-block; width:28px; height:28px; line-height:26px; text-align:center; border:1px solid #ccc; border-radius:4px; text-decoration:none; color:#333; font-weight:700;">
            −
          </a>
          <span class="qty" style="min-width:24px; text-align:center; font-weight:600;">
            <?php echo $item['SoLuong']; ?>
          </span>
          <a href="<?php echo BASE_URL; ?>/book/qty?vi=<?php echo $item['vitri']; ?>&action=tang"
             class="btn-qty"
             style="display:inline-block; width:28px; height:28px; line-height:26px; text-align:center; border:1px solid #ccc; border-radius:4px; text-decoration:none; color:#333; font-weight:700;">
            +
          </a>
        </div>

        <!-- Thành tiền -->
        <div class="cart-total" style="min-width:100px; text-align:right; font-weight:700; color:#333;">
          <?php echo number_format($item['ThanhTien'], 0, ',', '.'); ?> đ
        </div>

        <!-- Nút xóa -->
        <a href="<?php echo BASE_URL; ?>/book/removecart?vi=<?php echo $item['vitri']; ?>"
           class="btn-delete"
           style="color:#e74c3c; text-decoration:none; font-size:13px; white-space:nowrap;"
           onclick="return confirm('Xóa sản phẩm này?')">
          Xóa
        </a>

      </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>
  <!-- /.cart-left -->

  <!-- ══════════════════════════════════
       CỘT PHẢI — Form đặt hàng
  ══════════════════════════════════ -->
  <div class="cart-right" style="width:320px; background:#fff; border:1px solid #eee; border-radius:10px; padding:24px; align-self:flex-start;">

    <h3 style="margin-top:0; color:#333; border-bottom:1px solid #ddd; padding-bottom:12px; font-size:18px; font-weight:700;">
      ĐẶT HÀNG
    </h3>

    <form action="<?php echo BASE_URL; ?>/book/order" method="POST">

      <!-- Phương thức thanh toán -->
      <label style="font-weight:600; display:block; margin-bottom:6px;">
        Phương thức thanh toán
      </label>
      <select name="thanhtoan" class="form-control mb-3" style="width:100%; padding:8px; margin-bottom:14px; border:1px solid #ddd; border-radius:6px;">
        <option value="COD">Thanh toán khi nhận hàng (COD)</option>
        <option value="ATM">Chuyển khoản ngân hàng</option>
      </select>

      <!-- Cảnh báo / nhập thiếu thông tin giao hàng -->
      <?php if ($thieuThongTin): ?>
      <div style="color:#e74c3c; margin-bottom:10px; font-size:13px;">
        ⚠ Bạn chưa nhập thông tin nhận hàng.
      </div>
      <input type="text" name="sdt"
             placeholder="Số điện thoại" required
             class="form-control mb-2"
             style="width:100%; padding:8px; margin-bottom:8px; border:1px solid #ddd; border-radius:6px;">
      <input type="text" name="diachi"
             placeholder="Địa chỉ giao hàng" required
             class="form-control mb-2"
             style="width:100%; padding:8px; margin-bottom:14px; border:1px solid #ddd; border-radius:6px;">
      <?php endif; ?>

      <!-- Tổng thanh toán -->
      <div class="checkout-total" style="display:flex; justify-content:space-between; font-size:16px; font-weight:700; margin-bottom:18px; padding:12px 0; border-top:1px solid #eee; border-bottom:1px solid #eee;">
        <span>Tổng thanh toán</span>
        <span id="tong-tien" style="color:#e74c3c;">
          <?php echo number_format($tongCong, 0, ',', '.'); ?> VNĐ
        </span>
      </div>

      <button type="submit"
              class="btn btn-success w-100"
              style="width:100%; padding:12px; font-weight:700; font-size:15px; border-radius:8px;"
              onclick="return checkLogin()">
        HOÀN TẤT ĐẶT HÀNG
      </button>

    </form>

    <div style="margin-top:14px; text-align:center;">
      <a href="<?php echo BASE_URL; ?>/book"
         style="color:#888; font-size:13px; text-decoration:none;">
        ← Tiếp tục mua sắm
      </a>
    </div>

  </div>
  <!-- /.cart-right -->

</div>

<script>
// Tính lại tổng khi tick/untick checkbox
function tinhTien() {
    let tong = 0;
    document.querySelectorAll('.check-item:checked').forEach(cb => {
        tong += parseInt(cb.dataset.price || 0);
    });
    document.getElementById('tong-tien').innerText =
        tong.toLocaleString('vi-VN') + ' VNĐ';
}

document.querySelectorAll('.check-item').forEach(cb => {
    cb.addEventListener('change', tinhTien);
});

const checkAll = document.getElementById('check-all');
if (checkAll) {
    checkAll.addEventListener('change', function () {
        document.querySelectorAll('.check-item').forEach(cb => { cb.checked = this.checked; });
        tinhTien();
    });
}

document.addEventListener('DOMContentLoaded', tinhTien);

// Kiểm tra đăng nhập trước khi đặt hàng
function checkLogin() {
    <?php if (!isset($_SESSION['IDNguoiDung'])): ?>
        alert('⚠ Vui lòng đăng nhập để đặt hàng!');
        window.location.href = '<?php echo BASE_URL; ?>/auth/login';
        return false;
    <?php endif; ?>
    return true;
}
</script>
