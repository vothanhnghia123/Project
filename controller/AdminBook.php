<?php
require_once "model/ModelAdmin.php";

class AdminBook {
    private $model;

    public function __construct() {
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }
        $this->model = new ModelAdmin();
    }

    // ── Danh sách sách ─────────────────────────────────────────
    public function index() {
        $sachs    = $this->model->getAllSach();
        $theloais = $this->model->getAllTheloai();
        $nxbs     = $this->model->getAllNXB();
        $tacgias  = $this->model->getAllTacgia();
        $msg      = $_SESSION['admin_msg'] ?? '';
        unset($_SESSION['admin_msg']);

        require_once "view/admin/book/index.php";
    }

    // ── Form thêm mới ──────────────────────────────────────────
    public function add() {
        $sach     = null;
        $theloais = $this->model->getAllTheloai();
        $nxbs     = $this->model->getAllNXB();
        $tacgias  = $this->model->getAllTacgia();

        require_once "view/admin/book/form.php";
    }

    // ── Lưu sách mới ───────────────────────────────────────────
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminBook&action=index');
            exit();
        }

        $d       = $this->sanitizeBookPost();
        $hinhanh = $this->uploadImage('hinhanh');

        if ($hinhanh === false) {
            $_SESSION['admin_msg'] = 'Lỗi upload ảnh!';
            header('Location: index.php?controller=AdminBook&action=index');
            exit();
        }

        $this->model->insertSach($d, $hinhanh ?? '');
        $_SESSION['admin_msg'] = 'Thêm sách thành công!';
        header('Location: index.php?controller=AdminBook&action=index');
        exit();
    }

    // ── Form sửa sách ──────────────────────────────────────────
    public function edit() {
        $id   = (int)($_GET['param'] ?? 0);
        $sach = $this->model->getSachById($id);

        if (!$sach) {
            header('Location: index.php?controller=AdminBook&action=index');
            exit();
        }

        $theloais = $this->model->getAllTheloai();
        $nxbs     = $this->model->getAllNXB();
        $tacgias  = $this->model->getAllTacgia();

        require_once "view/admin/book/form.php";
    }

    // ── Lưu sửa sách ───────────────────────────────────────────
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=AdminBook&action=index');
            exit();
        }

        $id      = (int)($_POST['id'] ?? 0);
        $d       = $this->sanitizeBookPost();
        $hinhanh = $this->uploadImage('hinhanh');

        if ($hinhanh === false) {
            $_SESSION['admin_msg'] = 'Lỗi upload ảnh!';
            header('Location: index.php?controller=AdminBook&action=index');
            exit();
        }

        // Xoa anh cu neu co anh moi
        if ($hinhanh !== null) {
            $old = $this->model->getSachById($id);
            if ($old && !empty($old['HinhAnh'])) {
                $oldPath = 'public/images/sach/' . $old['HinhAnh'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
        }

        $this->model->updateSach($id, $d, $hinhanh);
        $_SESSION['admin_msg'] = 'Cập nhật sách thành công!';
        header('Location: index.php?controller=AdminBook&action=index');
        exit();
    }

    // ── Xóa sách ───────────────────────────────────────────────
    public function delete() {
        $id      = (int)($_GET['param'] ?? 0);
        $hinhanh = $this->model->deleteSach($id);

        if ($hinhanh) {
            $path = 'public/images/sach/' . $hinhanh;
            if (file_exists($path)) unlink($path);
        }

        $_SESSION['admin_msg'] = 'Đã xóa sách.';
        header('Location: index.php?controller=AdminBook&action=index');
        exit();
    }

    // ── Helpers ────────────────────────────────────────────────
    private function sanitizeBookPost() {
        return [
            'tensach'   => trim($_POST['tensach']   ?? ''),
            'idtheloai' => (int)($_POST['idtheloai'] ?? 0),
            'idnxb'     => (int)($_POST['idnxb']     ?? 0),
            'idtacgia'  => (int)($_POST['idtacgia']  ?? 0),
            'giaban'    => trim($_POST['giaban']      ?? '0'),
            'soluong'   => (int)($_POST['soluong']    ?? 0),
            'mota'      => trim($_POST['mota']        ?? ''),
            'sotrang'   => (int)($_POST['sotrang']    ?? 0),
            'namxb'     => trim($_POST['namxb']       ?? ''),
        ];
    }

    private function uploadImage($field) {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) return false;

        $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return false;

        $newName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES[$field]['name']);
        $destDir = 'public/images/sach/';
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);
        if (!move_uploaded_file($_FILES[$field]['tmp_name'], $destDir . $newName)) return false;

        return $newName;
    }
}
