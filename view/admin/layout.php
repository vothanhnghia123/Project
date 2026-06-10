<?php
// Helper: badge trang thai don hang (dung o nhieu view admin)
if (!function_exists('adminOrderBadge')) {
    function adminOrderBadge($tt) {
        switch ((int)$tt) {
            case 0: return '<span class="badge-status badge-wait">Chờ xác nhận</span>';
            case 1: return '<span class="badge-status badge-confirm">Đã xác nhận</span>';
            case 2: return '<span class="badge-status badge-prepare">Đang chuẩn bị</span>';
            case 3: return '<span class="badge-status badge-ship">Đang giao</span>';
            case 4: return '<span class="badge-status badge-done">Đã giao</span>';
            default: return '<span class="badge-status badge-cancel">Đã hủy</span>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Admin' : 'Admin Panel — BookStore'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="public/backend/css/adminstyle.css">
</head>
<body>

<!-- SIDEBAR -->
<aside class="admin-sidebar">

    <div class="sidebar-logo">
        <img src="public/images/bookstore_logo.png" alt="BookStore">
        <span>Admin Panel</span>
    </div>

    <?php
    $curCtrl = $_GET['controller'] ?? '';
    $curAct  = $_GET['action']     ?? '';
    function isActiveAdmin($ctrl) {
        return ($_GET['controller'] ?? '') === $ctrl ? 'active' : '';
    }
    ?>

    <nav class="sidebar-nav">

        <div class="nav-label">Tổng quan</div>
        <a href="index.php?controller=AdminHome&action=index" class="<?php echo isActiveAdmin('AdminHome'); ?>">
            <i class="fa-solid fa-chart-pie"></i> Dashboard
        </a>

        <div class="nav-label">Danh mục</div>
        <a href="index.php?controller=AdminDanhmuc&action=index" class="<?php echo isActiveAdmin('AdminDanhmuc'); ?>">
            <i class="fa-solid fa-folder-open"></i> Danh mục
        </a>
        <a href="index.php?controller=AdminTheloai&action=index" class="<?php echo isActiveAdmin('AdminTheloai'); ?>">
            <i class="fa-solid fa-tags"></i> Thể loại
        </a>

        <div class="nav-label">Sản phẩm</div>
        <a href="index.php?controller=AdminBook&action=index" class="<?php echo isActiveAdmin('AdminBook'); ?>">
            <i class="fa-solid fa-book"></i> Quản lý sách
        </a>
        <a href="index.php?controller=AdminTacgia&action=index" class="<?php echo isActiveAdmin('AdminTacgia'); ?>">
            <i class="fa-solid fa-pen-nib"></i> Tác giả
        </a>
        <a href="index.php?controller=AdminNxb&action=index" class="<?php echo isActiveAdmin('AdminNxb'); ?>">
            <i class="fa-solid fa-building-columns"></i> Nhà XB
        </a>

        <div class="nav-label">Kinh doanh</div>
        <a href="index.php?controller=AdminDonhang&action=index" class="<?php echo isActiveAdmin('AdminDonhang'); ?>">
            <i class="fa-solid fa-box"></i> Đơn hàng
        </a>

        <div class="nav-label">Hệ thống</div>
        <a href="index.php?controller=AdminNguoidung&action=index" class="<?php echo isActiveAdmin('AdminNguoidung'); ?>">
            <i class="fa-solid fa-users"></i> Người dùng
        </a>
        <a href="index.php?controller=AdminDanhgia&action=index" class="<?php echo isActiveAdmin('AdminDanhgia'); ?>">
            <i class="fa-solid fa-star"></i> Đánh giá
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Về trang web</a><br><br>
        <a href="index.php?controller=User&action=logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>

</aside>
<!-- /.admin-sidebar -->

<!-- MAIN -->
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

    <!-- Noi dung trang -->
    <div class="admin-content">
        <?php require_once "view/admin/route.php"; ?>
    </div>

</div>
<!-- /.admin-main -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</body>
</html>
