<?php
// ============================================================
//  View\Admin\Tacgia\Index — Quản lý tác giả
//  Biến: $tacgias (array), $editItem (array|null), $msg
// ============================================================
$pageTitle = 'Quản lý tác giả';
?>

<?php if (!empty($msg)): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 400px; gap:24px; align-items:start;">

    <!-- ════ BẢNG DANH SÁCH ════ -->
    <div class="admin-table-wrap">
        <div class="admin-table-header">
            <h3><i class="fa-solid fa-pen-nib" style="color:#e74c3c;margin-right:6px;"></i> Danh sách tác giả</h3>
            <span style="font-size:13px; color:#888;"><?php echo count($tacgias); ?> tác giả</span>
        </div>

        <table class="admin-tbl">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Tên tác giả</th>
                    <th>Tiểu sử</th>
                    <th style="width:130px; text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tacgias)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding:30px; color:#aaa;">
                            <i class="fa-solid fa-pen-nib" style="font-size:28px; display:block; margin-bottom:8px;"></i>
                            Chưa có tác giả nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tacgias as $tg): ?>
                    <tr>
                        <td><span style="color:#e74c3c; font-weight:700;">#<?php echo (int)$tg['IDTacGia']; ?></span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:9px;">
                                <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#9b59b6,#6c3483); display:flex; align-items:center; justify-content:center; color:#fff; font-size:14px; font-weight:700; flex-shrink:0;">
                                    <?php echo mb_strtoupper(mb_substr($tg['TenTacGia'], 0, 1)); ?>
                                </div>
                                <span style="font-weight:600;"><?php echo htmlspecialchars($tg['TenTacGia']); ?></span>
                            </div>
                        </td>
                        <td style="font-size:13px; color:#666; max-width:260px;">
                            <?php
                            $ts = htmlspecialchars($tg['TieuSu'] ?? '');
                            echo strlen($ts) > 80 ? mb_substr($ts, 0, 80) . '...' : ($ts ?: '<em style="color:#ccc;">Chưa có tiểu sử</em>');
                            ?>
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="<?php echo BASE_URL; ?>/admin/tacgia?edit=<?php echo (int)$tg['IDTacGia']; ?>"
                               class="btn-edit"><i class="fa-solid fa-pen-to-square"></i> Sửa</a>
                            <a href="<?php echo BASE_URL; ?>/admin/deletetacgia/<?php echo (int)$tg['IDTacGia']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Xóa tác giả này?')">
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
            <h3><i class="fa-solid fa-pen-to-square" style="color:#3498db; margin-right:8px;"></i> Sửa tác giả</h3>
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/updatetacgia/<?php echo (int)$editItem['IDTacGia']; ?>">
                <div class="form-row">
                    <label>Tên tác giả <span style="color:#e74c3c;">*</span></label>
                    <input type="text" name="tentacgia" required
                           value="<?php echo htmlspecialchars($editItem['TenTacGia']); ?>">
                </div>
                <div class="form-row">
                    <label>Tiểu sử</label>
                    <textarea name="tieusu" rows="4"
                              placeholder="Nhập tiểu sử tác giả..."><?php echo htmlspecialchars($editItem['TieuSu'] ?? ''); ?></textarea>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button type="submit" class="btn-submit" style="padding:9px 22px;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                    </button>
                    <a href="<?php echo BASE_URL; ?>/admin/tacgia" class="btn-cancel">
                        <i class="fa-solid fa-xmark"></i> Hủy
                    </a>
                </div>
            </form>
            <hr style="border:none; border-top:1px solid #f0f0f0; margin:20px 0;">
        <?php endif; ?>

        <h3><i class="fa-solid fa-plus-circle" style="color:#e74c3c; margin-right:8px;"></i> Thêm tác giả mới</h3>
        <form method="POST" action="<?php echo BASE_URL; ?>/admin/storetacgia">
            <div class="form-row">
                <label>Tên tác giả <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="tentacgia" required placeholder="VD: Nguyễn Nhật Ánh...">
            </div>
            <div class="form-row">
                <label>Tiểu sử</label>
                <textarea name="tieusu" rows="4" placeholder="Nhập tiểu sử tác giả (không bắt buộc)..."></textarea>
            </div>
            <button type="submit" class="btn-submit" style="padding:9px 22px;">
                <i class="fa-solid fa-plus"></i> Thêm tác giả
            </button>
        </form>
    </div>
</div>
