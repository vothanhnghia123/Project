<?php
require_once "model/ModelAdmin.php";

class AdminTacgia {
    private $model;

    public function __construct() {
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
        $this->model = new ModelAdmin();
    }

    public function index() {
        $tacgias  = $this->model->getAllTacgia();
        $editItem = isset($_GET['edit']) ? $this->model->getTacgiaById((int)$_GET['edit']) : null;
        $msg      = $_SESSION['admin_msg'] ?? '';
        unset($_SESSION['admin_msg']);

        require_once "view/admin/tacgia/index.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminTacgia&action=index');
            exit();
        }

        $ten    = trim($_POST['tentacgia'] ?? '');
        $tieusu = trim($_POST['tieusu']    ?? '');
        if ($ten !== '') $this->model->insertTacgia($ten, $tieusu);

        header('Location: index.php?controller=AdminTacgia&action=index');
        exit();
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminTacgia&action=index');
            exit();
        }

        $id     = (int)($_POST['id'] ?? 0);
        $ten    = trim($_POST['tentacgia'] ?? '');
        $tieusu = trim($_POST['tieusu']    ?? '');
        if ($ten !== '') $this->model->updateTacgia($id, $ten, $tieusu);

        header('Location: index.php?controller=AdminTacgia&action=index');
        exit();
    }

    public function delete() {
        $id = (int)($_GET['param'] ?? 0);
        $this->model->deleteTacgia($id);
        header('Location: index.php?controller=AdminTacgia&action=index');
        exit();
    }
}
