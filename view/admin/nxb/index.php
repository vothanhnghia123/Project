<?php $pageTitle = 'Quản lý nhà xuất bản'; ?>

<?php if (!empty($msg)): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 420px; gap:24px; align-items:start;">

    <!-- BANG DANH SACH -->
    <div class="admin-table-wrap">
        <div class="admin-table-header">
            <h3><i class="fa-solid fa-building-columns" style="color:#e74c3c;margin-right:6px;"></i> Danh sách nhà xuất bản</h3>
            <span style="font-size:13px; color:#888;"><?php echo count($nxbs); ?> NXB</span>
        </div>

        <table class="admin-tbl">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Tên NXB</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th style="width:130px; text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($nxbs)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:#aaa;">
                            Chưa có nhà xuất bản nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($nxbs as $nxb): ?>
                    <tr>
                        <td><span style="color:#e74c3c; font-weight:700;">#<?php echo (int)$nxb['IDNXB']; ?></span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:9px;">
                                <div style="width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,#1abc9c,#16a085); display:flex; align-items:center; justify-content:center; color:#fff; font-size:13px; font-weight:700; flex-shrink:0;">
                                    <?php echo mb_strtoupper(mb_substr($nxb['TenNXB'], 0, 1)); ?>
                                </div>
                                <span style="font-weight:600;"><?php echo htmlspecialchars($nxb['TenNXB']); ?></span>
                            </div>
                        </td>
                        <td style="font-size:13px; color:#666;">
                            <?php echo !empty($nxb['DiaChi']) ? htmlspecialchars($nxb['DiaChi']) : '<em style="color:#ccc;">—</em>'; ?>
                        </td>
                        <td style="font-size:13px; color:#666;">
                            <?php echo !empty($nxb['DienThoai']) ? htmlspecialchars($nxb['DienThoai']) : '<em style="color:#ccc;">—</em>'; ?>
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="index.php?controller=AdminNxb&action=index&edit=<?php echo (int)$nxb['IDNXB']; ?>"
                               class="btn-edit"><i class="fa-solid fa-pen-to-square"></i> Sửa</a>
                            <a href="index.php?controller=AdminNxb&action=delete&param=<?php echo (int)$nxb['IDNXB']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Xóa nhà xuất bản này?')">
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
            <h3><i class="fa-solid fa-pen-to-square" style="color:#3498db; margin-right:8px;"></i> Sửa nhà xuất bản</h3>
            <form method="POST" action="index.php?controller=AdminNxb&action=update">
                <input type="hidden" name="id" value="<?php echo (int)$editItem['IDNXB']; ?>">
                <div class="form-row">
                    <label>Tên NXB <span style="color:#e74c3c;">*</span></label>
                    <input type="text" name="tennxb" required
                           value="<?php echo htmlspecialchars($editItem['TenNXB']); ?>">
                </div>
                <div class="form-row">
                    <label>Địa chỉ</label>
                    <input type="text" name="diachi"
                           value="<?php echo htmlspecialchars($editItem['DiaChi'] ?? ''); ?>"
                           placeholder="Nhập địa chỉ...">
                </div>
                <div class="form-row">
                    <label>Điện thoại</label>
                    <input type="text" name="dienthoai"
                           value="<?php echo htmlspecialchars($editItem['DienThoai'] ?? ''); ?>"
                           placeholder="VD: 028 3838 xxxx">
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button type="submit" class="btn-submit" style="padding:9px 22px;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                    </button>
                    <a href="index.php?controller=AdminNxb&action=index" class="btn-cancel">
                        <i class="fa-solid fa-xmark"></i> Hủy
                    </a>
                </div>
            </form>
            <hr style="border:none; border-top:1px solid #f0f0f0; margin:20px 0;">
        <?php endif; ?>

        <h3><i class="fa-solid fa-plus-circle" style="color:#e74c3c; margin-right:8px;"></i> Thêm nhà xuất bản</h3>
        <form method="POST" action="index.php?controller=AdminNxb&action=store">
            <div class="form-row">
                <label>Tên NXB <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="tennxb" required placeholder="VD: NXB Kim Đồng...">
            </div>
            <div class="form-row">
                <label>Địa chỉ</label>
                <input type="text" name="diachi" placeholder="Nhập địa chỉ NXB...">
            </div>
            <div class="form-row">
                <label>Điện thoại</label>
                <input type="text" name="dienthoai" placeholder="VD: 028 3838 xxxx">
            </div>
            <button type="submit" class="btn-submit" style="padding:9px 22px;">
                <i class="fa-solid fa-plus"></i> Thêm NXB
            </button>
        </form>
    </div>
</div>
