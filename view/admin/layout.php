<?php
// ============================================================
//  View\Admin\Layout — Khung giao diện khu vực Admin
//  Biến $viewFile được truyền từ Admin::loadView()
// ============================================================

// Helper: badge trạng thái đơn hàng (dùng ở nhiều view admin)
if (!function_exists('adminOrderBadge')) {
    function adminOrderBadge(int $tt): string {
        return match($tt) {
            0 => '<span class="badge-status badge-wait">Chờ xác nhận</span>',
            1 => '<span class="badge-status badge-confirm">Đã xác nhận</span>',
            2 => '<span class="badge-status badge-prepare">Đang chuẩn bị</span>',
            3 => '<span class="badge-status badge-ship">Đang giao</span>',
            4 => '<span class="badge-status badge-done">Đã giao</span>',
            default => '<span class="badge-status badge-cancel">Đã hủy</span>',
        };
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Admin' : 'Admin Panel — BookStore'; ?></title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
    /* ── Reset & Base ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; display: flex; min-height: 100vh; }

    /* ── SIDEBAR ── */
    .admin-sidebar {
        width: 240px; min-height: 100vh; background: #1a1f2e;
        display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 100;
        overflow-y: auto;
    }
    .sidebar-logo {
        padding: 22px 20px 16px; border-bottom: 1px solid rgba(255,255,255,.08);
        text-align: center;
    }
    .sidebar-logo img { height: 42px; }
    .sidebar-logo span { display: block; color: #aab2c8; font-size: 11px; margin-top: 4px; letter-spacing: 1px; text-transform: uppercase; }

    .sidebar-nav { padding: 16px 0; flex: 1; }
    .nav-label { padding: 8px 20px 4px; font-size: 10px; color: #5a6480; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
    .sidebar-nav a {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 20px; color: #aab2c8; text-decoration: none;
        font-size: 14px; font-weight: 500; border-left: 3px solid transparent;
        transition: all .18s;
    }
    .sidebar-nav a:hover, .sidebar-nav a.active {
        background: rgba(255,255,255,.07); color: #fff; border-left-color: #e74c3c;
    }
    .sidebar-nav a i { width: 18px; text-align: center; font-size: 15px; }

    .sidebar-footer { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,.08); }
    .sidebar-footer a { color: #aab2c8; font-size: 13px; text-decoration: none; }
    .sidebar-footer a:hover { color: #e74c3c; }

    /* ── MAIN AREA ── */
    .admin-main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    /* ── TOP BAR ── */
    .admin-topbar {
        background: #fff; padding: 14px 28px;
        display: flex; justify-content: space-between; align-items: center;
        box-shadow: 0 1px 4px rgba(0,0,0,.08); position: sticky; top: 0; z-index: 50;
    }
    .topbar-title { font-size: 18px; font-weight: 700; color: #1a1f2e; }
    .topbar-user { display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555; }
    .topbar-user .avatar {
        width: 34px; height: 34px; border-radius: 50%; background: #e74c3c;
        display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px;
    }

    /* ── CONTENT ── */
    .admin-content { padding: 28px; flex: 1; }

    /* ── Thông báo flash ── */
    .alert-success {
        background: #eafaf1; color: #1e8449; border: 1px solid #a9dfbf;
        border-radius: 8px; padding: 10px 18px; margin-bottom: 18px; font-size: 14px;
    }

    /* ── Card thống kê ── */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap: 18px; margin-bottom: 28px; }
    .stat-card {
        background: #fff; border-radius: 12px; padding: 22px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,.06); display: flex; align-items: center; gap: 16px;
    }
    .stat-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff; flex-shrink: 0; }
    .stat-icon.red    { background: #e74c3c; }
    .stat-icon.blue   { background: #3498db; }
    .stat-icon.green  { background: #27ae60; }
    .stat-icon.orange { background: #f39c12; }
    .stat-icon.purple { background: #9b59b6; }
    .stat-icon.teal   { background: #1abc9c; }
    .stat-val  { font-size: 24px; font-weight: 700; color: #1a1f2e; line-height: 1.1; }
    .stat-label { font-size: 12px; color: #888; margin-top: 2px; }

    /* ── Bảng dữ liệu ── */
    .admin-table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); overflow: hidden; }
    .admin-table-header { padding: 18px 22px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; }
    .admin-table-header h3 { font-size: 16px; font-weight: 700; color: #1a1f2e; margin: 0; }
    .btn-add { background: #e74c3c; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .btn-add:hover { background: #c0392b; }

    table.admin-tbl { width: 100%; border-collapse: collapse; font-size: 14px; }
    table.admin-tbl th { background: #f8f9fa; padding: 11px 14px; text-align: left; font-weight: 600; color: #555; border-bottom: 2px solid #eee; white-space: nowrap; }
    table.admin-tbl td { padding: 11px 14px; border-bottom: 1px solid #f4f4f4; color: #444; vertical-align: middle; }
    table.admin-tbl tr:last-child td { border-bottom: none; }
    table.admin-tbl tr:hover td { background: #fafafa; }

    .btn-edit   { color: #3498db; text-decoration: none; font-weight: 600; font-size: 13px; }
    .btn-delete { color: #e74c3c; text-decoration: none; font-weight: 600; font-size: 13px; margin-left: 10px; }
    .btn-edit:hover   { text-decoration: underline; }
    .btn-delete:hover { text-decoration: underline; }

    /* ── Form Admin ── */
    .admin-form-wrap { background: #fff; border-radius: 12px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,.06); margin-bottom: 24px; }
    .admin-form-wrap h3 { font-size: 16px; font-weight: 700; color: #1a1f2e; margin-bottom: 20px; }
    .form-row { display: flex; flex-direction: column; margin-bottom: 16px; }
    .form-row label { font-weight: 600; font-size: 13px; color: #555; margin-bottom: 6px; }
    .form-row input, .form-row select, .form-row textarea {
        padding: 9px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;
        outline: none; transition: border-color .2s;
    }
    .form-row input:focus, .form-row select:focus, .form-row textarea:focus { border-color: #e74c3c; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .btn-submit { background: #e74c3c; color: #fff; border: none; padding: 10px 28px; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; }
    .btn-submit:hover { background: #c0392b; }
    .btn-cancel { background: #f0f0f0; color: #555; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; margin-left: 10px; }

    /* ── Badge trạng thái ── */
    .badge-status { padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .badge-wait    { background: #fef3c7; color: #92400e; }
    .badge-confirm { background: #dbeafe; color: #1e40af; }
    .badge-prepare { background: #fde68a; color: #78350f; }
    .badge-ship    { background: #d1fae5; color: #065f46; }
    .badge-done    { background: #bbf7d0; color: #14532d; }
    .badge-cancel  { background: #fee2e2; color: #991b1b; }

    /* ── Popup đơn hàng ── */
    #admin-popup {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5);
        z-index: 999; justify-content: center; align-items: center;
    }
    #admin-popup.show { display: flex; }
    .popup-box {
        background: #fff; border-radius: 14px; padding: 30px; max-width: 520px;
        width: 90%; max-height: 85vh; overflow-y: auto; position: relative;
        box-shadow: 0 8px 40px rgba(0,0,0,.18);
    }
    .popup-box h3 { font-size: 17px; font-weight: 700; margin-bottom: 16px; }
    .popup-box p  { margin-bottom: 8px; font-size: 14px; }
    .popup-close  {
        position: absolute; top: 14px; right: 18px;
        background: none; border: none; font-size: 20px; cursor: pointer; color: #888;
    }

    /* ── Responsive ── */
    @media(max-width: 768px) {
        .admin-sidebar { width: 200px; }
        .admin-main    { margin-left: 200px; }
        .form-grid-2   { grid-template-columns: 1fr; }
    }
    </style>
</head>
<body>

<!-- ══════════ SIDEBAR ══════════ -->
<aside class="admin-sidebar">

    <div class="sidebar-logo">
        <img src="<?php echo BASE_URL; ?>/public/images/bookstore_logo.png" alt="BookStore">
        <span>Admin Panel</span>
    </div>

    <?php
    $cur = strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    function isActive(string $path): string {
        $cur = strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        return (str_contains($cur, $path)) ? 'active' : '';
    }
    ?>

    <nav class="sidebar-nav">

        <div class="nav-label">Tổng quan</div>
        <a href="<?php echo BASE_URL; ?>/admin" class="<?php echo (rtrim($cur,'/')===rtrim(BASE_URL.'/admin','/')) ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-pie"></i> Dashboard
        </a>

        <div class="nav-label">Danh mục</div>
        <a href="<?php echo BASE_URL; ?>/admin/danhmuc"  class="<?php echo isActive('/admin/danhmuc'); ?>">
            <i class="fa-solid fa-folder-open"></i> Danh mục
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/theloai"  class="<?php echo isActive('/admin/theloai'); ?>">
            <i class="fa-solid fa-tags"></i> Thể loại
        </a>

        <div class="nav-label">Sản phẩm</div>
        <a href="<?php echo BASE_URL; ?>/admin/book"   class="<?php echo isActive('/admin/book'); ?>">
            <i class="fa-solid fa-book"></i> Quản lý sách
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/tacgia"  class="<?php echo isActive('/admin/tacgia'); ?>">
            <i class="fa-solid fa-pen-nib"></i> Tác giả
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/nxb"     class="<?php echo isActive('/admin/nxb'); ?>">
            <i class="fa-solid fa-building-columns"></i> Nhà XB
        </a>

        <div class="nav-label">Kinh doanh</div>
        <a href="<?php echo BASE_URL; ?>/admin/donhang"  class="<?php echo isActive('/admin/donhang'); ?>">
            <i class="fa-solid fa-box"></i> Đơn hàng
        </a>

        <div class="nav-label">Hệ thống</div>
        <a href="<?php echo BASE_URL; ?>/admin/nguoidung" class="<?php echo isActive('/admin/nguoidung'); ?>">
            <i class="fa-solid fa-users"></i> Người dùng
        </a>
        <a href="<?php echo BASE_URL; ?>/admin/danhgia"   class="<?php echo isActive('/admin/danhgia'); ?>">
            <i class="fa-solid fa-star"></i> Đánh giá
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="<?php echo BASE_URL; ?>/"><i class="fa-solid fa-arrow-left"></i> Về trang web</a><br><br>
        <a href="<?php echo BASE_URL; ?>/auth/logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>

</aside>
<!-- /.admin-sidebar -->

<!-- ══════════ MAIN ══════════ -->
<div class="admin-main">

    <!-- Top bar -->
    <div class="admin-topbar">
        <div class="topbar-title">
            <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?>
        </div>
        <div class="topbar-user">
            <div class="avatar"><i class="fa-solid fa-user"></i></div>
            <span><?php echo htmlspecialchars($_SESSION['HoTen'] ?? 'Admin'); ?></span>
        </div>
    </div>

    <!-- Nội dung trang -->
    <div class="admin-content">
        <?php require $viewFile; ?>
    </div>

</div>
<!-- /.admin-main -->

<!-- jQuery (dùng cho popup đơn hàng) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</body>
</html>
