<?php $pageTitle = 'Quản lý đánh giá'; ?>

<?php if (!empty($msg)): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <h3><i class="fa-solid fa-star" style="color:#e74c3c;margin-right:6px;"></i> Danh sách đánh giá</h3>
        <div style="display:flex; align-items:center; gap:12px;">
            <span style="font-size:13px; color:#888;"><?php echo count($danhgias); ?> đánh giá</span>
            <select id="filter-sao" onchange="filterSao()"
                    style="padding:6px 10px; border:1px solid #ddd; border-radius:8px; font-size:13px; outline:none;">
                <option value="">Tất cả số sao</option>
                <option value="5">⭐⭐⭐⭐⭐ (5 sao)</option>
                <option value="4">⭐⭐⭐⭐ (4 sao)</option>
                <option value="3">⭐⭐⭐ (3 sao)</option>
                <option value="2">⭐⭐ (2 sao)</option>
                <option value="1">⭐ (1 sao)</option>
            </select>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="admin-tbl" id="dg-table">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th style="min-width:160px;">Sách</th>
                    <th>Người đánh giá</th>
                    <th style="width:100px; text-align:center;">Số sao</th>
                    <th>Nội dung</th>
                    <th style="width:130px;">Ngày đánh giá</th>
                    <th style="width:80px; text-align:center;">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($danhgias)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#aaa;">
                            Chưa có đánh giá nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($danhgias as $dg): ?>
                    <?php $sao = (int)$dg['SoSao']; ?>
                    <tr data-sao="<?php echo $sao; ?>">
                        <td><span style="color:#e74c3c; font-weight:700;">#<?php echo (int)$dg['IDDanhGia']; ?></span></td>
                        <td>
                            <span style="font-weight:600; color:#1a1f2e; display:block; max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?php echo htmlspecialchars($dg['TenSach']); ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg,#3498db,#2980b9); display:flex; align-items:center; justify-content:center; color:#fff; font-size:12px; font-weight:700; flex-shrink:0;">
                                    <?php echo mb_strtoupper(mb_substr($dg['HoTen'], 0, 1)); ?>
                                </div>
                                <span style="font-size:13px;"><?php echo htmlspecialchars($dg['HoTen']); ?></span>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            $starColors = [1=>'#e74c3c', 2=>'#e67e22', 3=>'#f1c40f', 4=>'#2ecc71', 5=>'#f1c40f'];
                            $color = $starColors[$sao] ?? '#f1c40f';
                            echo '<span style="color:' . $color . '; font-size:14px; letter-spacing:1px;">';
                            echo str_repeat('★', $sao) . str_repeat('☆', 5 - $sao);
                            echo '</span>';
                            echo '<br><span style="font-size:11px; color:#888;">' . $sao . '/5</span>';
                            ?>
                        </td>
                        <td style="font-size:13px; color:#555; max-width:260px;">
                            <?php
                            $nd = htmlspecialchars($dg['NoiDung'] ?? '');
                            echo strlen($nd) > 100
                                ? mb_substr($nd, 0, 100) . '...'
                                : ($nd ?: '<em style="color:#ccc;">Không có nội dung</em>');
                            ?>
                        </td>
                        <td style="font-size:12px; color:#888; white-space:nowrap;">
                            <?php
                            $ngay = $dg['NgayDanhGia'] ?? ($dg['created_at'] ?? null);
                            echo $ngay ? date('d/m/Y H:i', strtotime($ngay)) : '—';
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <a href="index.php?controller=AdminDanhgia&action=delete&param=<?php echo (int)$dg['IDDanhGia']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Xóa đánh giá này?')"
                               style="background:#fee2e2; padding:5px 10px; border-radius:6px; font-size:12px; margin:0;">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($danhgias)): ?>
    <div style="padding:12px 22px; border-top:1px solid #f0f0f0; display:flex; gap:20px; flex-wrap:wrap;">
        <?php
        $counts = array_count_values(array_column($danhgias, 'SoSao'));
        $total  = count($danhgias);
        $avg    = $total > 0 ? round(array_sum(array_column($danhgias, 'SoSao')) / $total, 1) : 0;
        ?>
        <span style="font-size:13px; color:#888;">
            <i class="fa-solid fa-star" style="color:#f1c40f;"></i>
            Trung bình: <b style="color:#1a1f2e;"><?php echo $avg; ?>/5</b>
        </span>
        <?php for ($s = 5; $s >= 1; $s--): ?>
        <span style="font-size:13px; color:#888;">
            <?php echo str_repeat('★', $s); ?>: <b style="color:#1a1f2e;"><?php echo $counts[$s] ?? 0; ?></b>
        </span>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function filterSao() {
    const val = document.getElementById('filter-sao').value;
    document.querySelectorAll('#dg-table tbody tr').forEach(tr => {
        tr.style.display = (!val || tr.dataset.sao === val) ? '' : 'none';
    });
}
</script>
