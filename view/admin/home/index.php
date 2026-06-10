<?php
$pageTitle = 'Dashboard';

// Chuan bi du lieu bieu do 7 ngay
$labels = [];
$values = [];
for ($i = 6; $i >= 0; $i--) {
    $date     = date('Y-m-d', strtotime("-{$i} days"));
    $labels[] = date('d/m', strtotime($date));
    $values[$date] = 0;
}
foreach ($doanhThu7Ngay as $row) {
    $values[$row['ngay']] = (float)$row['tong'];
}
$chartData = array_values($values);
?>

<!-- STAT CARDS -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-coins"></i></div>
        <div>
            <div class="stat-val"><?php echo number_format($doanhThuHomNay, 0, ',', '.'); ?>đ</div>
            <div class="stat-label">Doanh thu hôm nay</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div>
        <div>
            <div class="stat-val"><?php echo number_format($doanhThuThang, 0, ',', '.'); ?>đ</div>
            <div class="stat-label">Doanh thu tháng này</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
        <div>
            <div class="stat-val"><?php echo $soDonCho; ?></div>
            <div class="stat-label">Đơn chờ xác nhận</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-book"></i></div>
        <div>
            <div class="stat-val"><?php echo $tongSach; ?></div>
            <div class="stat-label">Tổng số sách</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-users"></i></div>
        <div>
            <div class="stat-val"><?php echo $tongKhach; ?></div>
            <div class="stat-label">Khách hàng</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fa-solid fa-box"></i></div>
        <div>
            <div class="stat-val"><?php echo $tongDon; ?></div>
            <div class="stat-label">Tổng đơn hàng</div>
        </div>
    </div>
</div>

<!-- GRID: Bieu do + Don moi nhat -->
<div style="display:grid; grid-template-columns:1.6fr 1fr; gap:20px; align-items:start;">

    <!-- Bieu do doanh thu 7 ngay -->
    <div class="admin-table-wrap" style="padding:22px;">
        <h3 style="font-size:15px; font-weight:700; margin-bottom:16px; color:#1a1f2e;">
            <i class="fa-solid fa-chart-bar" style="color:#e74c3c; margin-right:6px;"></i>
            Doanh thu 7 ngày qua
        </h3>
        <canvas id="revenueChart" height="120"></canvas>
    </div>

    <!-- Don hang moi nhat -->
    <div class="admin-table-wrap">
        <div class="admin-table-header">
            <h3>Đơn hàng mới nhất</h3>
            <a href="index.php?controller=AdminDonhang&action=index"
               style="font-size:13px; color:#e74c3c;">Xem tất cả →</a>
        </div>
        <table class="admin-tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Tiền</th>
                    <th>TT</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($donMoiNhat)): ?>
                    <tr><td colspan="4" style="text-align:center; color:#aaa; padding:20px;">Chưa có đơn hàng</td></tr>
                <?php else: ?>
                    <?php foreach ($donMoiNhat as $don): ?>
                    <tr>
                        <td><b style="color:#e74c3c;">#<?php echo (int)$don['IDDonHang']; ?></b></td>
                        <td style="font-size:13px;"><?php echo htmlspecialchars($don['HoTen']); ?></td>
                        <td style="font-size:13px; white-space:nowrap;">
                            <?php echo number_format((float)$don['TongTien'], 0, ',', '.'); ?>đ
                        </td>
                        <td><?php echo adminOrderBadge((int)$don['TrangThai']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data:  <?php echo json_encode($chartData); ?>,
            backgroundColor: 'rgba(231,76,60,.7)',
            borderColor: '#e74c3c',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => (v >= 1000000 ? (v/1000000).toFixed(1)+'M' : v.toLocaleString()) + 'đ'
                }
            }
        }
    }
});
</script>
