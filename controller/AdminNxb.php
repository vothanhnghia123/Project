<?php
require_once "model/ModelAdmin.php";

class AdminNxb {
    private $model;

    public function __construct() {
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
        $this->model = new ModelAdmin();
    }

    public function index() {
        $nxbs     = $this->model->getAllNXB();
        $editItem = isset($_GET['edit']) ? $this->model->getNXBById((int)$_GET['edit']) : null;
        $msg      = $_SESSION['admin_msg'] ?? '';
        unset($_SESSION['admin_msg']);

        require_once "view/admin/nxb/index.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminNxb&action=index');
            exit();
        }

        $ten    = trim($_POST['tennxb']    ?? '');
        $diachi = trim($_POST['diachi']    ?? '');
        $sdt    = trim($_POST['dienthoai'] ?? '');
        if ($ten !== '') $this->model->insertNXB($ten, $diachi, $sdt);

        header('Location: index.php?controller=AdminNxb&action=index');
        exit();
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminNxb&action=index');
            exit();
        }

        $id     = (int)($_POST['id'] ?? 0);
        $ten    = trim($_POST['tennxb']    ?? '');
        $diachi = trim($_POST['diachi']    ?? '');
        $sdt    = trim($_POST['dienthoai'] ?? '');
        if ($ten !== '') $this->model->updateNXB($id, $ten, $diachi, $sdt);

        header('Location: index.php?controller=AdminNxb&action=index');
        exit();
    }

    public function delete() {
        $id = (int)($_GET['param'] ?? 0);
        $this->model->deleteNXB($id);
        header('Location: index.php?controller=AdminNxb&action=index');
        exit();
    }
}
