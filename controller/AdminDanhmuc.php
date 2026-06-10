<?php
require_once "model/ModelAdmin.php";

class AdminDanhmuc {
    private $model;

    public function __construct() {
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
        $this->model = new ModelAdmin();
    }

    public function index() {
        $danhmucs = $this->model->getAllDanhmuc();
        $editItem = isset($_GET['edit']) ? $this->model->getDanhmucById((int)$_GET['edit']) : null;
        $msg      = $_SESSION['admin_msg'] ?? '';
        unset($_SESSION['admin_msg']);

        require_once "view/admin/danhmuc/index.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminDanhmuc&action=index');
            exit();
        }

        $ten = trim($_POST['tendanhmuc'] ?? '');
        if ($ten !== '') $this->model->insertDanhmuc($ten);

        header('Location: index.php?controller=AdminDanhmuc&action=index');
        exit();
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminDanhmuc&action=index');
            exit();
        }

        $id  = (int)($_POST['id'] ?? 0);
        $ten = trim($_POST['tendanhmuc'] ?? '');
        if ($ten !== '') $this->model->updateDanhmuc($id, $ten);

        header('Location: index.php?controller=AdminDanhmuc&action=index');
        exit();
    }

    public function delete() {
        $id = (int)($_GET['param'] ?? 0);
        $this->model->deleteDanhmuc($id);
        header('Location: index.php?controller=AdminDanhmuc&action=index');
        exit();
    }
}
