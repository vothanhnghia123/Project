<?php
require_once "model/ModelAdmin.php";

class AdminDonhang {
    private $model;

    public function __construct() {
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
        $this->model = new ModelAdmin();
    }

    public function index() {
        $donhangs = $this->model->getAllDonhang();
        require_once "view/admin/donhang/index.php";
    }

    // AJAX: cap nhat trang thai don hang
    public function updatetrangthai() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo 'error'; return; }

        $id        = (int)($_POST['id']        ?? 0);
        $trangThai = (int)($_POST['trangthai'] ?? 0);

        if ($id > 0) {
            $this->model->updateTrangThaiDonhang($id, $trangThai);
            echo 'ok';
        } else {
            echo 'error';
        }
    }

    // AJAX: load chi tiet san pham trong don hang
    public function loadchitiet() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo ''; return; }

        $id    = (int)($_POST['id'] ?? 0);
        $items = $id > 0 ? $this->model->getChitietDonhang($id) : [];

        foreach ($items as $row) {
            echo '<p>'
               . htmlspecialchars($row['TenSach'])
               . ' — SL: ' . (int)$row['SoLuong']
               . ' — Giá: ' . number_format((float)$row['DonGia'], 0, ',', '.') . ' VNĐ'
               . '</p>';
        }
    }
}
