<?php
// ============================================================
//  View\Admin\Danhmuc\Index — Quản lý danh mục
//  Biến: $danhmucs (array), $editItem (array|null), $msg (string)
// ============================================================
$pageTitle = 'Quản lý danh mục';
?>

<?php if (!empty($msg)): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 380px; gap:24px; align-items:start;">

    <!-- ════ BẢNG DANH SÁCH ════ -->
    <div class="admin-table-wrap">
        <div class="admin-table-header">
            <h3><i class="fa-solid fa-folder-open" style="color:#e74c3c;margin-right:6px;"></i> Danh sách danh mục</h3>
            <span style="font-size:13px; color:#888;"><?php echo count($danhmucs); ?> danh mục</span>
        </div>

        <table class="admin-tbl">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Tên danh mục</th>
                    <th style="width:130px; text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danhmucs)): ?>
                    <tr>
                        <td colspan="3" style="text-align:center; padding:30px; color:#aaa;">
                            <i class="fa-solid fa-folder-open" style="font-size:28px; display:block; margin-bottom:8px;"></i>
                            Chưa có danh mục nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($danhmucs as $dm): ?>
                    <tr>
                        <td><span style="color:#e74c3c; font-weight:700;">#<?php echo (int)$dm['IDDanhMuc']; ?></span></td>
                        <td>
                            <i class="fa-solid fa-folder" style="color:#f39c12; margin-right:6px;"></i>
                            <span style="font-weight:600;"><?php echo htmlspecialchars($dm['TenDanhMuc']); ?></span>
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="<?php echo BASE_URL; ?>/admin/danhmuc?edit=<?php echo (int)$dm['IDDanhMuc']; ?>"
                               class="btn-edit"><i class="fa-solid fa-pen-to-square"></i> Sửa</a>
                            <a href="<?php echo BASE_URL; ?>/admin/deletedanhmuc/<?php echo (int)$dm['IDDanhMuc']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Xóa danh mục này? Các thể loại bên trong có thể bị ảnh hưởng!')">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ════ FORM THÊM / SỬA ════ -->
    <div class="admin-form-wrap" style="margin-bottom:0;">
        <?php if ($editItem): ?>
            <!-- FORM SỬA -->
            <h3><i class="fa-solid fa-pen-to-square" style="color:#3498db; margin-right:8px;"></i> Sửa danh mục</h3>
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/updatedanhmuc/<?php echo (int)$editItem['IDDanhMuc']; ?>">
                <div class="form-row">
                    <label for="tendanhmuc-edit">Tên danh mục <span style="color:#e74c3c;">*</span></label>
                    <input type="text" id="tendanhmuc-edit" name="tendanhmuc" required
                           value="<?php echo htmlspecialchars($editItem['TenDanhMuc']); ?>">
                </div>
                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <button type="submit" class="btn-submit" style="padding:9px 22px;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                    </button>
                    <a href="<?php echo BASE_URL; ?>/admin/danhmuc" class="btn-cancel">
                        <i class="fa-solid fa-xmark"></i> Hủy
                    </a>
                </div>
            </form>

            <hr style="border:none; border-top:1px solid #f0f0f0; margin:20px 0;">
        <?php endif; ?>

        <!-- FORM THÊM MỚI (luôn hiển thị) -->
        <h3><i class="fa-solid fa-plus-circle" style="color:#e74c3c; margin-right:8px;"></i> Thêm danh mục mới</h3>
        <form method="POST" action="<?php echo BASE_URL; ?>/admin/storedanhmuc">
            <div class="form-row">
                <label for="tendanhmuc">Tên danh mục <span style="color:#e74c3c;">*</span></label>
                <input type="text" id="tendanhmuc" name="tendanhmuc" required
                       placeholder="VD: Văn học, Khoa học...">
            </div>
            <button type="submit" class="btn-submit" style="padding:9px 22px;">
                <i class="fa-solid fa-plus"></i> Thêm danh mục
            </button>
        </form>
    </div>

</div>
