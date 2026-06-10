<?php
require_once "model/ModelAdmin.php";

class AdminTheloai {
    private $model;

    public function __construct() {
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
        $this->model = new ModelAdmin();
    }

    public function index() {
        $theloais = $this->model->getAllTheloai();
        $danhmucs = $this->model->getAllDanhmuc();
        $editItem = isset($_GET['edit']) ? $this->model->getTheloaiById((int)$_GET['edit']) : null;
        $msg      = $_SESSION['admin_msg'] ?? '';
        unset($_SESSION['admin_msg']);

        require_once "view/admin/theloai/index.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminTheloai&action=index');
            exit();
        }

        $ten  = trim($_POST['tentheloai'] ?? '');
        $idDm = (int)($_POST['iddanhmuc'] ?? 0);
        if ($ten !== '' && $idDm > 0) $this->model->insertTheloai($ten, $idDm);

        header('Location: index.php?controller=AdminTheloai&action=index');
        exit();
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminTheloai&action=index');
            exit();
        }

        $id   = (int)($_POST['id'] ?? 0);
        $ten  = trim($_POST['tentheloai'] ?? '');
        $idDm = (int)($_POST['iddanhmuc'] ?? 0);
        if ($ten !== '' && $idDm > 0) $this->model->updateTheloai($id, $ten, $idDm);

        header('Location: index.php?controller=AdminTheloai&action=index');
        exit();
    }

    public function delete() {
        $id = (int)($_GET['param'] ?? 0);
        $this->model->deleteTheloai($id);
        header('Location: index.php?controller=AdminTheloai&action=index');
        exit();
    }
}
