<?php
/**
 * view/auth/profile.php
 * Trang thông tin người dùng — 3 tab: Thông tin | Đơn hàng | Đổi mật khẩu
 *
 * Biến nhận từ controller:
 *   $user   – mảng thông tin người dùng
 *   $page   – tab đang chọn: 'info' | 'orders' | 'password'
 *   $orders – mảng đơn hàng (chỉ có khi $page === 'orders')
 *   $msgOk  – thông báo thành công
 *   $msgErr – thông báo lỗi
 */

// Helper nhãn trạng thái đơn hàng
function trangThaiBadge(int $tt): string {
    return match($tt) {
        0 => '<span class="badge bg-secondary">Chờ xác nhận</span>',
        1 => '<span class="badge bg-info text-dark">Đã xác nhận</span>',
        2 => '<span class="badge bg-warning text-dark">Đang chuẩn bị</span>',
        3 => '<span class="badge bg-primary">Đang giao</span>',
        4 => '<span class="badge bg-success">Đã giao</span>',
        default => '<span class="badge bg-danger">Đã hủy</span>',
    };
}
?>

<div class="profile-container">

    <h2>Thông tin người dùng</h2>

    <!-- ── Thông báo ── -->
    <?php if ($msgOk !== ''): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="msg-ok">
            <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($msgOk) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($msgErr !== ''): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($msgErr) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="profile-content">

        <!-- ══ Cột trái: menu ══ -->
        <div class="profile-buttons">

            <a href="<?= BASE_URL ?>/auth/profile?tab=info"
               class="btn <?= $page === 'info' ? 'btn-active' : '' ?>">
                <i class="fa-solid fa-user"></i> Thông tin
            </a>

            <a href="<?= BASE_URL ?>/auth/profile?tab=orders"
               class="btn <?= $page === 'orders' ? 'btn-active' : '' ?>">
                <i class="fa-solid fa-box"></i> Đơn hàng của tôi
            </a>

            <a href="<?= BASE_URL ?>/auth/profile?tab=password"
               class="btn <?= $page === 'password' ? 'btn-active' : '' ?>">
                <i class="fa-solid fa-key"></i> Đổi mật khẩu
            </a>

            <a href="<?= BASE_URL ?>/auth/logout" class="btn logout"
               style="color:#e53935; border-color:#e53935;">
                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
            </a>

        </div>

        <!-- ══ Cột phải: nội dung ══ -->
        <div class="user-info">

            <?php if ($page === 'info'): ?>
            <!-- ── Tab Thông tin ── -->
            <form method="POST" action="<?= BASE_URL ?>/auth/profile?tab=info">

                <div class="info-row">
                    <span>Họ tên:</span>
                    <input type="text" name="hoten"
                           value="<?= htmlspecialchars($user['HoTen'] ?? '') ?>" required>
                </div>

                <div class="info-row">
                    <span>Email:</span>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($user['Email'] ?? '') ?>" required>
                </div>

                <div class="info-row">
                    <span>Điện thoại:</span>
                    <input type="text" name="dienthoai"
                           value="<?= htmlspecialchars($user['DienThoai'] ?? '') ?>"
                           placeholder="Chưa cập nhật">
                </div>

                <div class="info-row">
                    <span>Địa chỉ:</span>
                    <input type="text" name="diachi"
                           value="<?= htmlspecialchars($user['DiaChi'] ?? '') ?>"
                           placeholder="Chưa cập nhật">
                </div>

                <div class="save-box" style="margin-top:20px;">
                    <button type="submit" name="luu" class="btn save"
                            style="background:#e53935; color:white; border:none; padding:10px 25px; border-radius:6px; cursor:pointer;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                    </button>
                </div>

            </form>

            <?php elseif ($page === 'orders'): ?>
            <!-- ── Tab Đơn hàng ── -->
            <?php if (empty($orders)): ?>
                <p style="color:#888; padding:20px 0;">Bạn chưa có đơn hàng nào.</p>
            <?php else: ?>
            <table class="order-table table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Số lượng</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['IDDonHang'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['NgayDat'])) ?></td>
                        <td><?= number_format((float)$order['TongTien'], 0, ',', '.') ?> đ</td>
                        <td><?= $order['TongSoLuong'] ?></td>
                        <td><?= htmlspecialchars($order['PhuongThucTT']) ?></td>
                        <td><?= trangThaiBadge((int)$order['TrangThai']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <?php elseif ($page === 'password'): ?>
            <!-- ── Tab Đổi mật khẩu ── -->
            <form method="POST" action="<?= BASE_URL ?>/auth/profile?tab=password">

                <div class="info-row">
                    <span>Mật khẩu cũ:</span>
                    <div class="password-input" style="display:flex; align-items:center; gap:8px; flex:1;">
                        <input type="password" name="oldpass" id="oldpass"
                               style="flex:1; padding:8px 5px; border:none; outline:none; font-size:15px;">
                        <span class="show-pass"
                              onclick="togglePass('oldpass', this)"
                              style="cursor:pointer; color:#e53935; font-size:13px; white-space:nowrap;">Hiện</span>
                    </div>
                </div>

                <div class="info-row">
                    <span>Mật khẩu mới:</span>
                    <input type="password" name="newpass"
                           placeholder="Tối thiểu 6 ký tự">
                </div>

                <div class="info-row">
                    <span>Nhập lại:</span>
                    <input type="password" name="renewpass"
                           placeholder="Nhập lại mật khẩu mới">
                </div>

                <div class="save-box" style="margin-top:20px;">
                    <button type="submit" name="doimatkhau"
                            style="background:#e53935; color:white; border:none; padding:10px 25px; border-radius:6px; cursor:pointer;">
                        <i class="fa-solid fa-key"></i> Đổi mật khẩu
                    </button>
                </div>

            </form>
            <?php endif; ?>

        </div><!-- /user-info -->

    </div><!-- /profile-content -->

</div><!-- /profile-container -->

<script>
// Hiện / ẩn mật khẩu cũ
function togglePass(fieldId, btn) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
        btn.textContent = 'Ẩn';
    } else {
        field.type = 'password';
        btn.textContent = 'Hiện';
    }
}

// Tự ẩn thông báo thành công sau 5 giây
setTimeout(function () {
    const ok = document.getElementById('msg-ok');
    if (ok) ok.style.display = 'none';
}, 5000);
</script>
