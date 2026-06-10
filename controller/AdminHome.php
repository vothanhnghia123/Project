<?php
require_once "model/ModelAdmin.php";

class AdminHome {
    private $model;

    public function __construct() {
        // Kiem tra quyen admin
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
        $this->model = new ModelAdmin();
    }

    // ── Dashboard ──────────────────────────────────────────────
    public function index() {
        $doanhThuHomNay = $this->model->getDoanhThuHomNay();
        $doanhThuThang  = $this->model->getDoanhThuThangNay();
        $soDonCho       = $this->model->getSoDonChoXacNhan();
        $tongSach       = $this->model->getTongSach();
        $tongKhach      = $this->model->getTongKhachHang();
        $tongDon        = $this->model->getTongDonHang();
        $donMoiNhat     = $this->model->getDonHangMoiNhat(5);
        $doanhThu7Ngay  = $this->model->getDoanhThu7Ngay();

        require_once "view/admin/home/index.php";
    }
}
