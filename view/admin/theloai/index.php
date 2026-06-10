<?php $pageTitle = 'Quản lý thể loại'; ?>

<?php if (!empty($msg)): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 400px; gap:24px; align-items:start;">

    <!-- BANG DANH SACH -->
    <div class="admin-table-wrap">
        <div class="admin-table-header">
            <h3><i class="fa-solid fa-tags" style="color:#e74c3c;margin-right:6px;"></i> Danh sách thể loại</h3>
            <span style="font-size:13px; color:#888;"><?php echo count($theloais); ?> thể loại</span>
        </div>

        <table class="admin-tbl">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Tên thể loại</th>
                    <th>Danh mục</th>
                    <th style="width:130px; text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($theloais)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding:30px; color:#aaa;">Chưa có thể loại nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($theloais as $tl): ?>
                    <tr>
                        <td><span style="color:#e74c3c; font-weight:700;">#<?php echo (int)$tl['IDTheLoai']; ?></span></td>
                        <td>
                            <i class="fa-solid fa-tag" style="color:#3498db; margin-right:6px;"></i>
                            <span style="font-weight:600;"><?php echo htmlspecialchars($tl['TenTheLoai']); ?></span>
                        </td>
                        <td>
                            <span style="background:#fff8e1; color:#e67e22; padding:3px 9px; border-radius:12px; font-size:12px; font-weight:600;">
                                <?php echo htmlspecialchars($tl['TenDanhMuc'] ?? '—'); ?>
                            </span>
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="index.php?controller=AdminTheloai&action=index&edit=<?php echo (int)$tl['IDTheLoai']; ?>"
                               class="btn-edit"><i class="fa-solid fa-pen-to-square"></i> Sửa</a>
                            <a href="index.php?controller=AdminTheloai&action=delete&param=<?php echo (int)$tl['IDTheLoai']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Xóa thể loại này?')">
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
            <h3><i class="fa-solid fa-pen-to-square" style="color:#3498db; margin-right:8px;"></i> Sửa thể loại</h3>
            <form method="POST" action="index.php?controller=AdminTheloai&action=update">
                <input type="hidden" name="id" value="<?php echo (int)$editItem['IDTheLoai']; ?>">
                <div class="form-row">
                    <label>Tên thể loại <span style="color:#e74c3c;">*</span></label>
                    <input type="text" name="tentheloai" required
                           value="<?php echo htmlspecialchars($editItem['TenTheLoai']); ?>">
                </div>
                <div class="form-row">
                    <label>Danh mục <span style="color:#e74c3c;">*</span></label>
                    <select name="iddanhmuc" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($danhmucs as $dm): ?>
                            <option value="<?php echo (int)$dm['IDDanhMuc']; ?>"
                                <?php echo ((int)$dm['IDDanhMuc'] === (int)$editItem['IDDanhMuc']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dm['TenDanhMuc']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button type="submit" class="btn-submit" style="padding:9px 22px;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                    </button>
                    <a href="index.php?controller=AdminTheloai&action=index" class="btn-cancel">
                        <i class="fa-solid fa-xmark"></i> Hủy
                    </a>
                </div>
            </form>
            <hr style="border:none; border-top:1px solid #f0f0f0; margin:20px 0;">
        <?php endif; ?>

        <h3><i class="fa-solid fa-plus-circle" style="color:#e74c3c; margin-right:8px;"></i> Thêm thể loại mới</h3>
        <form method="POST" action="index.php?controller=AdminTheloai&action=store">
            <div class="form-row">
                <label>Tên thể loại <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="tentheloai" required placeholder="VD: Tiểu thuyết, Truyện tranh...">
            </div>
            <div class="form-row">
                <label>Danh mục <span style="color:#e74c3c;">*</span></label>
                <select name="iddanhmuc" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($danhmucs as $dm): ?>
                        <option value="<?php echo (int)$dm['IDDanhMuc']; ?>">
                            <?php echo htmlspecialchars($dm['TenDanhMuc']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit" style="padding:9px 22px;">
                <i class="fa-solid fa-plus"></i> Thêm thể loại
            </button>
        </form>
    </div>
</div>
