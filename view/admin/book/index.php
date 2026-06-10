<?php $pageTitle = 'Quản lý sách'; ?>

<?php if (!empty($msg)): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <h3><i class="fa-solid fa-book" style="color:#e74c3c;margin-right:6px;"></i> Danh sách sách</h3>
        <a href="index.php?controller=AdminBook&action=add" class="btn-add">
            <i class="fa-solid fa-plus"></i> Thêm sách mới
        </a>
    </div>

    <?php if (!empty($sachs)): ?>
    <div style="padding:12px 22px; border-top:1px solid #f0f0f0; font-size:13px; color:#888; display:flex; gap:20px;">
        <span><i class="fa-solid fa-layer-group" style="color:#e74c3c;"></i> Tổng: <b style="color:#1a1f2e;"><?php echo count($sachs); ?></b> sách</span>
        <span><i class="fa-solid fa-tags" style="color:#3498db;"></i> Thể loại: <b style="color:#1a1f2e;"><?php echo count($theloais); ?></b></span>
        <span><i class="fa-solid fa-building-columns" style="color:#27ae60;"></i> NXB: <b style="color:#1a1f2e;"><?php echo count($nxbs); ?></b></span>
        <span><i class="fa-solid fa-pen-nib" style="color:#9b59b6;"></i> Tác giả: <b style="color:#1a1f2e;"><?php echo count($tacgias); ?></b></span>
    </div>
    <?php endif; ?>

    <div style="overflow-x:auto;">
        <table class="admin-tbl">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Tên sách</th>
                    <th>Thể loại</th>
                    <th>NXB</th>
                    <th>Tác giả</th>
                    <th style="text-align:center;">SL</th>
                    <th>Giá bán</th>
                    <th>Năm XB</th>
                    <th style="width:80px; text-align:center;">Hình</th>
                    <th style="width:110px; text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sachs)): ?>
                    <tr>
                        <td colspan="10" style="text-align:center; padding:30px; color:#aaa;">
                            Chưa có sách nào trong danh sách.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($sachs as $row): ?>
                    <tr>
                        <td><span style="color:#e74c3c; font-weight:700;">#<?php echo (int)$row['IDSach']; ?></span></td>
                        <td>
                            <span style="font-weight:600; color:#1a1f2e; display:block; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?php echo htmlspecialchars($row['TenSach']); ?>
                            </span>
                        </td>
                        <td>
                            <span style="background:#f0f4ff; color:#3a5bd9; padding:3px 9px; border-radius:12px; font-size:12px; font-weight:600;">
                                <?php echo htmlspecialchars($row['TenTheLoai'] ?? '—'); ?>
                            </span>
                        </td>
                        <td style="font-size:13px; color:#666;"><?php echo htmlspecialchars($row['TenNXB'] ?? '—'); ?></td>
                        <td style="font-size:13px; color:#666;"><?php echo htmlspecialchars($row['TenTacGia'] ?? '—'); ?></td>
                        <td style="text-align:center;">
                            <?php
                            $sl = (int)$row['SoLuong'];
                            $slColor = $sl <= 0 ? '#e74c3c' : ($sl <= 5 ? '#f39c12' : '#27ae60');
                            ?>
                            <span style="font-weight:700; color:<?php echo $slColor; ?>;"><?php echo $sl; ?></span>
                        </td>
                        <td style="font-weight:600; color:#e74c3c; white-space:nowrap;">
                            <?php echo number_format((float)$row['GiaBan'], 0, ',', '.'); ?>đ
                        </td>
                        <td style="font-size:13px; color:#888;"><?php echo htmlspecialchars($row['NamXB'] ?? '—'); ?></td>
                        <td style="text-align:center;">
                            <?php if (!empty($row['HinhAnh'])): ?>
                                <img src="public/images/sach/<?php echo htmlspecialchars($row['HinhAnh']); ?>"
                                     alt="Bìa sách"
                                     style="width:50px; height:65px; object-fit:cover; border-radius:4px; box-shadow:0 1px 4px rgba(0,0,0,.15);">
                            <?php else: ?>
                                <span style="color:#ccc; font-size:12px;">Chưa có</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="index.php?controller=AdminBook&action=edit&param=<?php echo (int)$row['IDSach']; ?>"
                               class="btn-edit">
                                <i class="fa-solid fa-pen-to-square"></i> Sửa
                            </a>
                            <a href="index.php?controller=AdminBook&action=delete&param=<?php echo (int)$row['IDSach']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Xóa sách này? Hành động không thể hoàn tác!')">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
