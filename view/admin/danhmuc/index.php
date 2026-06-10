<?php $pageTitle = 'Quản lý danh mục'; ?>

<?php if (!empty($msg)): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 380px; gap:24px; align-items:start;">

    <!-- BANG DANH SACH -->
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
                            <a href="index.php?controller=AdminDanhmuc&action=index&edit=<?php echo (int)$dm['IDDanhMuc']; ?>"
                               class="btn-edit"><i class="fa-solid fa-pen-to-square"></i> Sửa</a>
                            <a href="index.php?controller=AdminDanhmuc&action=delete&param=<?php echo (int)$dm['IDDanhMuc']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Xóa danh mục này?')">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- FORM THEM / SUA -->
    <div class="admin-form-wrap" style="margin-bottom:0;">
        <?php if ($editItem): ?>
            <h3><i class="fa-solid fa-pen-to-square" style="color:#3498db; margin-right:8px;"></i> Sửa danh mục</h3>
            <form method="POST" action="index.php?controller=AdminDanhmuc&action=update">
                <input type="hidden" name="id" value="<?php echo (int)$editItem['IDDanhMuc']; ?>">
                <div class="form-row">
                    <label>Tên danh mục <span style="color:#e74c3c;">*</span></label>
                    <input type="text" name="tendanhmuc" required
                           value="<?php echo htmlspecialchars($editItem['TenDanhMuc']); ?>">
                </div>
                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <button type="submit" class="btn-submit" style="padding:9px 22px;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                    </button>
                    <a href="index.php?controller=AdminDanhmuc&action=index" class="btn-cancel">
                        <i class="fa-solid fa-xmark"></i> Hủy
                    </a>
                </div>
            </form>
            <hr style="border:none; border-top:1px solid #f0f0f0; margin:20px 0;">
        <?php endif; ?>

        <h3><i class="fa-solid fa-plus-circle" style="color:#e74c3c; margin-right:8px;"></i> Thêm danh mục mới</h3>
        <form method="POST" action="index.php?controller=AdminDanhmuc&action=store">
            <div class="form-row">
                <label>Tên danh mục <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="tendanhmuc" required placeholder="VD: Văn học, Khoa học...">
            </div>
            <button type="submit" class="btn-submit" style="padding:9px 22px;">
                <i class="fa-solid fa-plus"></i> Thêm danh mục
            </button>
        </form>
    </div>

</div>
