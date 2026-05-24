<?php
// ============================================================
//  View\Admin\Book\Form — Form thêm / sửa sách
//  Biến: $theloais, $nxbs, $tacgias
//        $sach (array|null) — null = chế độ thêm mới
// ============================================================
$isEdit    = !empty($sach);
$pageTitle = $isEdit ? 'Sửa sách: ' . htmlspecialchars($sach['TenSach']) : 'Thêm sách mới';
$action    = $isEdit
    ? BASE_URL . '/admin/updatebook/' . (int)$sach['IDSach']
    : BASE_URL . '/admin/storebook';
?>

<!-- Breadcrumb -->
<div style="margin-bottom:18px; font-size:13px; color:#888;">
    <a href="<?php echo BASE_URL; ?>/admin/book" style="color:#e74c3c; text-decoration:none;">
        <i class="fa-solid fa-book"></i> Quản lý sách
    </a>
    <span style="margin:0 8px;">›</span>
    <span><?php echo $isEdit ? 'Chỉnh sửa' : 'Thêm mới'; ?></span>
</div>

<div class="admin-form-wrap">
    <h3>
        <i class="fa-solid fa-<?php echo $isEdit ? 'pen-to-square' : 'plus-circle'; ?>" style="color:#e74c3c; margin-right:8px;"></i>
        <?php echo $isEdit ? 'Chỉnh sửa thông tin sách' : 'Thêm sách mới'; ?>
    </h3>

    <form method="POST" action="<?php echo $action; ?>" enctype="multipart/form-data">

        <!-- Hàng 1: Tên sách (full width) -->
        <div class="form-row">
            <label for="tensach">Tên sách <span style="color:#e74c3c;">*</span></label>
            <input type="text" id="tensach" name="tensach" required
                   placeholder="Nhập tên sách..."
                   value="<?php echo htmlspecialchars($sach['TenSach'] ?? ''); ?>">
        </div>

        <!-- Hàng 2: Thể loại / NXB / Tác giả -->
        <div class="form-grid-2" style="grid-template-columns:1fr 1fr 1fr;">
            <div class="form-row" style="margin-bottom:0;">
                <label for="idtheloai">Thể loại <span style="color:#e74c3c;">*</span></label>
                <select id="idtheloai" name="idtheloai" required>
                    <option value="">-- Chọn thể loại --</option>
                    <?php foreach ($theloais as $tl): ?>
                        <option value="<?php echo (int)$tl['IDTheLoai']; ?>"
                            <?php echo (isset($sach['IDTheLoai']) && (int)$sach['IDTheLoai'] === (int)$tl['IDTheLoai']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tl['TenTheLoai']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row" style="margin-bottom:0;">
                <label for="idnxb">Nhà xuất bản <span style="color:#e74c3c;">*</span></label>
                <select id="idnxb" name="idnxb" required>
                    <option value="">-- Chọn NXB --</option>
                    <?php foreach ($nxbs as $nxb): ?>
                        <option value="<?php echo (int)$nxb['IDNXB']; ?>"
                            <?php echo (isset($sach['IDNXB']) && (int)$sach['IDNXB'] === (int)$nxb['IDNXB']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($nxb['TenNXB']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row" style="margin-bottom:0;">
                <label for="idtacgia">Tác giả <span style="color:#e74c3c;">*</span></label>
                <select id="idtacgia" name="idtacgia" required>
                    <option value="">-- Chọn tác giả --</option>
                    <?php foreach ($tacgias as $tg): ?>
                        <option value="<?php echo (int)$tg['IDTacGia']; ?>"
                            <?php echo (isset($sach['IDTacGia']) && (int)$sach['IDTacGia'] === (int)$tg['IDTacGia']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tg['TenTacGia']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="height:16px;"></div>

        <!-- Hàng 3: Giá / Số lượng / Số trang / Năm XB -->
        <div class="form-grid-2" style="grid-template-columns:1fr 1fr 1fr 1fr;">
            <div class="form-row" style="margin-bottom:0;">
                <label for="giaban">Giá bán (VNĐ) <span style="color:#e74c3c;">*</span></label>
                <input type="number" id="giaban" name="giaban" min="0" required
                       placeholder="VD: 85000"
                       value="<?php echo htmlspecialchars($sach['GiaBan'] ?? ''); ?>">
            </div>
            <div class="form-row" style="margin-bottom:0;">
                <label for="soluong">Số lượng tồn kho</label>
                <input type="number" id="soluong" name="soluong" min="0"
                       placeholder="VD: 100"
                       value="<?php echo htmlspecialchars($sach['SoLuong'] ?? ''); ?>">
            </div>
            <div class="form-row" style="margin-bottom:0;">
                <label for="sotrang">Số trang</label>
                <input type="number" id="sotrang" name="sotrang" min="0"
                       placeholder="VD: 320"
                       value="<?php echo htmlspecialchars($sach['SoTrang'] ?? ''); ?>">
            </div>
            <div class="form-row" style="margin-bottom:0;">
                <label for="namxb">Năm xuất bản</label>
                <input type="text" id="namxb" name="namxb"
                       placeholder="VD: 2023"
                       value="<?php echo htmlspecialchars($sach['NamXB'] ?? ''); ?>">
            </div>
        </div>

        <div style="height:16px;"></div>

        <!-- Hàng 4: Mô tả -->
        <div class="form-row">
            <label for="mota">Mô tả sách</label>
            <textarea id="mota" name="mota" rows="5"
                      placeholder="Nhập mô tả nội dung sách..."><?php echo htmlspecialchars($sach['MoTa'] ?? ''); ?></textarea>
        </div>

        <!-- Hàng 5: Upload ảnh -->
        <div class="form-row">
            <label for="hinhanh">
                Hình ảnh bìa sách
                <?php if ($isEdit): ?><span style="font-weight:400; color:#aaa;">(Bỏ trống nếu không muốn thay đổi)</span><?php endif; ?>
            </label>

            <?php if ($isEdit && !empty($sach['HinhAnh'])): ?>
            <div style="display:flex; align-items:flex-start; gap:16px; flex-wrap:wrap;">
                <div>
                    <img id="preview-img"
                         src="<?php echo BASE_URL; ?>/public/images/sach/<?php echo htmlspecialchars($sach['HinhAnh']); ?>"
                         alt="Bìa sách hiện tại"
                         style="width:90px; height:120px; object-fit:cover; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,.15); border:2px solid #eee;">
                    <p style="font-size:11px; color:#aaa; margin-top:4px; text-align:center;">Ảnh hiện tại</p>
                </div>
                <div style="flex:1;">
                    <input type="file" id="hinhanh" name="hinhanh" accept="image/jpeg,image/png,image/webp"
                           onchange="previewImage(this)">
                    <p style="font-size:12px; color:#aaa; margin-top:6px;">Chấp nhận: JPG, PNG, WEBP. Tối đa 5MB.</p>
                </div>
            </div>
            <?php else: ?>
                <img id="preview-img" src="" alt="" style="display:none; width:90px; height:120px; object-fit:cover; border-radius:6px; margin-bottom:8px; box-shadow:0 2px 8px rgba(0,0,0,.15);">
                <input type="file" id="hinhanh" name="hinhanh" accept="image/jpeg,image/png,image/webp"
                       onchange="previewImage(this)">
                <p style="font-size:12px; color:#aaa; margin-top:6px;">Chấp nhận: JPG, PNG, WEBP. Tối đa 5MB.</p>
            <?php endif; ?>
        </div>

        <!-- Nút Submit -->
        <div style="display:flex; align-items:center; gap:12px; margin-top:8px;">
            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-<?php echo $isEdit ? 'floppy-disk' : 'plus'; ?>"></i>
                <?php echo $isEdit ? 'Lưu thay đổi' : 'Thêm sách'; ?>
            </button>
            <a href="<?php echo BASE_URL; ?>/admin/book" class="btn-cancel">
                <i class="fa-solid fa-xmark"></i> Hủy
            </a>
        </div>

    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
