<?php
// Helper nhan trang thai don hang
function trangThaiBadge($tt) {
    switch ((int)$tt) {
        case 0: return '<span class="badge bg-secondary">Chờ xác nhận</span>';
        case 1: return '<span class="badge bg-info text-dark">Đã xác nhận</span>';
        case 2: return '<span class="badge bg-warning text-dark">Đang chuẩn bị</span>';
        case 3: return '<span class="badge bg-primary">Đang giao</span>';
        case 4: return '<span class="badge bg-success">Đã giao</span>';
        default: return '<span class="badge bg-danger">Đã hủy</span>';
    }
}
?>

<div class="profile-container">

    <h2>Thông tin người dùng</h2>

    <!-- Thong bao -->
    <?php if ($msgOk !== ''): ?>
        <div class="alert alert-success" id="msg-ok">
            <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($msgOk) ?>
        </div>
    <?php endif; ?>
    <?php if ($msgErr !== ''): ?>
        <div class="alert alert-danger">
            <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($msgErr) ?>
        </div>
    <?php endif; ?>

    <div class="profile-content">

        <!-- Cot trai: menu -->
        <div class="profile-buttons">

            <a href="index.php?controller=User&action=profile&tab=info"
               class="btn <?= $page === 'info' ? 'btn-active' : '' ?>">
                <i class="fa-solid fa-user"></i> Thông tin
            </a>

            <a href="index.php?controller=User&action=profile&tab=orders"
               class="btn <?= $page === 'orders' ? 'btn-active' : '' ?>">
                <i class="fa-solid fa-box"></i> Đơn hàng của tôi
            </a>

            <a href="index.php?controller=User&action=profile&tab=password"
               class="btn <?= $page === 'password' ? 'btn-active' : '' ?>">
                <i class="fa-solid fa-key"></i> Đổi mật khẩu
            </a>

            <a href="index.php?controller=User&action=logout"
               class="btn logout"
               style="color:#e53935; border-color:#e53935;">
                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
            </a>

        </div>

        <!-- Cot phai: noi dung -->
        <div class="user-info">

            <?php if ($page === 'info'): ?>
            <!-- Tab Thong tin -->
            <form method="POST" action="index.php?controller=User&action=profile&tab=info">

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
                    <button type="submit" name="luu"
                            style="background:#e53935; color:white; border:none; padding:10px 25px; border-radius:6px; cursor:pointer;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                    </button>
                </div>

            </form>

            <?php elseif ($page === 'orders'): ?>
            <!-- Tab Don hang -->
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
            <!-- Tab Doi mat khau -->
            <form method="POST" action="index.php?controller=User&action=profile&tab=password">

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

setTimeout(function () {
    const ok = document.getElementById('msg-ok');
    if (ok) ok.style.display = 'none';
}, 5000);
</script>
