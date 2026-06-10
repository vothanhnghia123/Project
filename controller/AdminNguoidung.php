<?php
require_once "model/ModelAdmin.php";

class AdminNguoidung {
    private $model;

    public function __construct() {
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
        $this->model = new ModelAdmin();
    }

    public function index() {
        $users = $this->model->getAllNguoidung();
        require_once "view/admin/nguoidung/index.php";
    }

    // AJAX: cap quyen Admin
    public function capquyen() {
        $id = (int)($_GET['param'] ?? 0);
        $this->model->setVaiTro($id, 1);
        echo 'ok';
    }

    // AJAX: ha ve quyen User
    public function haquyen() {
        $id = (int)($_GET['param'] ?? 0);
        // Khong duoc tu ha quyen chinh minh
        if ($id === (int)$_SESSION['IDNguoiDung']) { echo 'self'; return; }
        $this->model->setVaiTro($id, 2);
        echo 'ok';
    }
}
