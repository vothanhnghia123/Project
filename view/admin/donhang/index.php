<?php $pageTitle = 'Quản lý đơn hàng'; ?>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <h3><i class="fa-solid fa-box" style="color:#e74c3c;margin-right:6px;"></i> Danh sách đơn hàng</h3>
        <div style="display:flex; align-items:center; gap:12px;">
            <span style="font-size:13px; color:#888;"><?php echo count($donhangs); ?> đơn</span>
            <select id="filter-tt" onchange="filterTable()"
                    style="padding:6px 10px; border:1px solid #ddd; border-radius:8px; font-size:13px; outline:none;">
                <option value="">Tất cả trạng thái</option>
                <option value="0">Chờ xác nhận</option>
                <option value="1">Đã xác nhận</option>
                <option value="2">Đang chuẩn bị</option>
                <option value="3">Đang giao</option>
                <option value="4">Đã giao</option>
                <option value="5">Đã hủy</option>
            </select>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="admin-tbl" id="don-table">
            <thead>
                <tr>
                    <th style="width:70px;">#Đơn</th>
                    <th>Khách hàng</th>
                    <th>Điện thoại</th>
                    <th>Ngày đặt</th>
                    <th style="text-align:right;">Tổng tiền</th>
                    <th style="text-align:center;">Trạng thái</th>
                    <th style="width:100px; text-align:center;">Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($donhangs)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#aaa;">
                            Chưa có đơn hàng nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($donhangs as $don): ?>
                    <tr data-tt="<?php echo (int)$don['TrangThai']; ?>">
                        <td>
                            <span style="color:#e74c3c; font-weight:700;">#<?php echo (int)$don['IDDonHang']; ?></span>
                        </td>
                        <td>
                            <div style="font-weight:600; color:#1a1f2e;"><?php echo htmlspecialchars($don['HoTen']); ?></div>
                            <div style="font-size:12px; color:#888;"><?php echo htmlspecialchars($don['Email'] ?? ''); ?></div>
                        </td>
                        <td style="font-size:13px; color:#666; white-space:nowrap;">
                            <?php echo htmlspecialchars($don['DienThoai'] ?? '—'); ?>
                        </td>
                        <td style="font-size:13px; color:#666; white-space:nowrap;">
                            <?php echo date('d/m/Y H:i', strtotime($don['NgayDat'])); ?>
                        </td>
                        <td style="text-align:right; font-weight:700; color:#e74c3c; white-space:nowrap;">
                            <?php echo number_format((float)$don['TongTien'], 0, ',', '.'); ?>đ
                        </td>
                        <td style="text-align:center;">
                            <span class="status-cell" id="status-<?php echo (int)$don['IDDonHang']; ?>">
                                <?php echo adminOrderBadge((int)$don['TrangThai']); ?>
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <button class="btn-add" style="padding:5px 12px; font-size:12px;"
                                    onclick="openPopup(
                                        <?php echo (int)$don['IDDonHang']; ?>,
                                        <?php echo (int)$don['TrangThai']; ?>,
                                        '<?php echo htmlspecialchars(addslashes($don['HoTen'])); ?>',
                                        '<?php echo htmlspecialchars(addslashes($don['Email'] ?? '')); ?>',
                                        '<?php echo htmlspecialchars(addslashes($don['DienThoai'] ?? '')); ?>',
                                        '<?php echo htmlspecialchars(addslashes($don['DiaChi'] ?? '')); ?>',
                                        '<?php echo date('d/m/Y H:i', strtotime($don['NgayDat'])); ?>',
                                        '<?php echo number_format((float)$don['TongTien'], 0, ',', '.'); ?>'
                                    )">
                                <i class="fa-solid fa-eye"></i> Xem
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- POPUP CHI TIET DON HANG -->
<div id="admin-popup">
    <div class="popup-box">
        <button class="popup-close" onclick="closePopup()"><i class="fa-solid fa-xmark"></i></button>
        <h3><i class="fa-solid fa-box" style="color:#e74c3c; margin-right:8px;"></i>
            Chi tiết đơn hàng <span id="pop-id" style="color:#e74c3c;"></span>
        </h3>

        <!-- Thong tin nguoi mua -->
        <div style="background:#f8f9fa; border-radius:10px; padding:16px; margin-bottom:16px;">
            <div style="font-weight:700; color:#1a1f2e; margin-bottom:10px; font-size:13px;">
                <i class="fa-solid fa-user" style="color:#3498db;"></i> Thông tin người mua
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; font-size:13px;">
                <div><span style="color:#888;">Họ tên:</span> <b id="pop-ten"></b></div>
                <div><span style="color:#888;">Email:</span> <span id="pop-email"></span></div>
                <div><span style="color:#888;">SĐT:</span> <span id="pop-sdt"></span></div>
                <div><span style="color:#888;">Ngày đặt:</span> <span id="pop-ngay"></span></div>
            </div>
            <div style="margin-top:8px; font-size:13px;">
                <span style="color:#888;">Địa chỉ:</span> <span id="pop-diachi"></span>
            </div>
            <div style="margin-top:6px; font-size:13px;">
                <span style="color:#888;">Tổng tiền:</span> <b style="color:#e74c3c;" id="pop-tien"></b>
            </div>
        </div>

        <!-- San pham trong don -->
        <div style="margin-bottom:16px;">
            <div style="font-weight:700; color:#1a1f2e; margin-bottom:10px; font-size:13px;">
                <i class="fa-solid fa-list" style="color:#27ae60;"></i> Sản phẩm trong đơn
            </div>
            <div id="pop-sanpham">
                <span style="color:#aaa;"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải...</span>
            </div>
        </div>

        <!-- Cap nhat trang thai -->
        <div style="border-top:1px solid #f0f0f0; padding-top:16px;">
            <label style="font-weight:700; font-size:13px; display:block; margin-bottom:8px;">
                <i class="fa-solid fa-truck" style="color:#f39c12;"></i> Cập nhật trạng thái
            </label>
            <input type="hidden" id="pop-don-id">
            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <select id="pop-tt" style="flex:1; min-width:180px; padding:9px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; outline:none;">
                    <option value="0">⏳ Chờ xác nhận</option>
                    <option value="1">✅ Đã xác nhận</option>
                    <option value="2">📦 Đang chuẩn bị</option>
                    <option value="3">🚚 Đang giao</option>
                    <option value="4">🎉 Đã giao</option>
                    <option value="5">❌ Đã hủy</option>
                </select>
                <button class="btn-submit" onclick="saveStatus()" style="padding:9px 20px;">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu
                </button>
            </div>
            <p id="pop-msg" style="font-size:13px; color:#27ae60; margin-top:8px; display:none;">
                <i class="fa-solid fa-circle-check"></i> Đã cập nhật trạng thái!
            </p>
        </div>
    </div>
</div>

<script>
function openPopup(id, tt, ten, email, sdt, diachi, ngay, tien) {
    document.getElementById('pop-id').textContent    = '#' + id;
    document.getElementById('pop-ten').textContent   = ten;
    document.getElementById('pop-email').textContent = email;
    document.getElementById('pop-sdt').textContent   = sdt;
    document.getElementById('pop-diachi').textContent = diachi;
    document.getElementById('pop-ngay').textContent  = ngay;
    document.getElementById('pop-tien').textContent  = tien + 'đ';
    document.getElementById('pop-don-id').value      = id;
    document.getElementById('pop-tt').value          = tt;
    document.getElementById('pop-msg').style.display = 'none';

    document.getElementById('pop-sanpham').innerHTML =
        '<span style="color:#aaa;"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải...</span>';

    $.ajax({
        url: 'index.php?controller=AdminDonhang&action=loadchitiet',
        method: 'POST',
        data: { id: id },
        success: function(data) {
            document.getElementById('pop-sanpham').innerHTML =
                data || '<em style="color:#aaa;">Không có sản phẩm</em>';
        },
        error: function() {
            document.getElementById('pop-sanpham').innerHTML =
                '<em style="color:#e74c3c;">Không thể tải dữ liệu</em>';
        }
    });

    document.getElementById('admin-popup').classList.add('show');
}

function closePopup() {
    document.getElementById('admin-popup').classList.remove('show');
}

function saveStatus() {
    const id = document.getElementById('pop-don-id').value;
    const tt = document.getElementById('pop-tt').value;

    $.ajax({
        url: 'index.php?controller=AdminDonhang&action=updatetrangthai',
        method: 'POST',
        data: { id: id, trangthai: tt },
        success: function(res) {
            if (res.trim() === 'ok') {
                const badgeMap = {
                    0: '<span class="badge-status badge-wait">Chờ xác nhận</span>',
                    1: '<span class="badge-status badge-confirm">Đã xác nhận</span>',
                    2: '<span class="badge-status badge-prepare">Đang chuẩn bị</span>',
                    3: '<span class="badge-status badge-ship">Đang giao</span>',
                    4: '<span class="badge-status badge-done">Đã giao</span>',
                    5: '<span class="badge-status badge-cancel">Đã hủy</span>',
                };
                const cell = document.getElementById('status-' + id);
                if (cell) cell.innerHTML = badgeMap[tt] || '';
                const row = cell ? cell.closest('tr') : null;
                if (row) row.dataset.tt = tt;
                document.getElementById('pop-msg').style.display = 'block';
            }
        }
    });
}

function filterTable() {
    const val = document.getElementById('filter-tt').value;
    document.querySelectorAll('#don-table tbody tr').forEach(tr => {
        tr.style.display = (!val || tr.dataset.tt === val) ? '' : 'none';
    });
}

document.getElementById('admin-popup').addEventListener('click', function(e) {
    if (e.target === this) closePopup();
});
</script>
