<?php
require_once "model/ModelAdmin.php";

class AdminDanhgia {
    private $model;

    public function __construct() {
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
        $this->model = new ModelAdmin();
    }

    public function index() {
        $danhgias = $this->model->getAllDanhgia();
        $msg      = $_SESSION['admin_msg'] ?? '';
        unset($_SESSION['admin_msg']);

        require_once "view/admin/danhgia/index.php";
    }

    public function delete() {
        $id = (int)($_GET['param'] ?? 0);
        $this->model->deleteDanhgia($id);
        $_SESSION['admin_msg'] = 'Đã xóa đánh giá.';
        header('Location: index.php?controller=AdminDanhgia&action=index');
        exit();
    }
}
