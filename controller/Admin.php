<?php
// ============================================================
//  Controller\Admin — Trung tâm điều hướng khu vực quản trị
//
//  URL map:
//    GET  /admin                     → index()          Dashboard
//    GET  /admin/book                → book()           DS sách
//    GET  /admin/addbook             → addbook()        Form thêm sách
//    POST /admin/storebook           → storebook()      Lưu sách mới
//    GET  /admin/editbook/{id}       → editbook($id)    Form sửa sách
//    POST /admin/updatebook/{id}     → updatebook($id)  Lưu sửa sách
//    GET  /admin/deletebook/{id}     → deletebook($id)  Xóa sách
//
//    GET  /admin/danhmuc             → danhmuc()        DS danh mục
//    POST /admin/storedanhmuc        → storedanhmuc()   Lưu DM mới
//    POST /admin/updatedanhmuc/{id}  → updatedanhmuc()  Sửa DM
//    GET  /admin/deletedanhmuc/{id}  → deletedanhmuc()  Xóa DM
//
//    GET  /admin/theloai             → theloai()        DS thể loại
//    POST /admin/storetheloai        → storetheloai()   Lưu TL mới
//    POST /admin/updatetheloai/{id}  → updatetheloai()  Sửa TL
//    GET  /admin/deletetheloai/{id}  → deletetheloai()  Xóa TL
//
//    GET  /admin/tacgia              → tacgia()         DS tác giả
//    POST /admin/storetacgia         → storetacgia()    Lưu TG mới
//    POST /admin/updatetacgia/{id}   → updatetacgia()   Sửa TG
//    GET  /admin/deletetacgia/{id}   → deletetacgia()   Xóa TG
//
//    GET  /admin/nxb                 → nxb()            DS nhà XB
//    POST /admin/storenxb            → storenxb()       Lưu NXB mới
//    POST /admin/updatenxb/{id}      → updatenxb()      Sửa NXB
//    GET  /admin/deletenxb/{id}      → deletenxb()      Xóa NXB
//
//    GET  /admin/donhang             → donhang()        DS đơn hàng
//    POST /admin/updatedonhang       → updatedonhang()  Cập nhật trạng thái (AJAX)
//    POST /admin/loadchitiet         → loadchitiet()    Load SP đơn (AJAX)
//
//    GET  /admin/nguoidung           → nguoidung()      DS người dùng
//    GET  /admin/capquyen/{id}       → capquyen($id)    Cấp admin (AJAX)
//    GET  /admin/haquyen/{id}        → haquyen($id)     Hạ quyền (AJAX)
//
//    GET  /admin/danhgia             → danhgia()        DS đánh giá
//    GET  /admin/deletedanhgia/{id}  → deletedanhgia()  Xóa đánh giá
// ============================================================

require_once BASE_PATH . '/model/Admin.php';

class Admin
{
    private AdminModel $model;

    public function __construct()
    {
        $this->requireAdmin();
        $this->model = new AdminModel();
    }

    // ══════════════════════════════════════════════════════════
    //  DASHBOARD
    // ══════════════════════════════════════════════════════════
    public function index(): void
    {
        $data = [
            'doanhThuHomNay'   => $this->model->getDoanhThuHomNay(),
            'doanhThuThang'    => $this->model->getDoanhThuThangNay(),
            'soDonCho'         => $this->model->getSoDonChoXacNhan(),
            'tongSach'         => $this->model->getTongSach(),
            'tongKhach'        => $this->model->getTongKhachHang(),
            'tongDon'          => $this->model->getTongDonHang(),
            'donMoiNhat'       => $this->model->getDonHangMoiNhat(5),
            'doanhThu7Ngay'    => $this->model->getDoanhThu7Ngay(),
        ];
        $this->loadView('admin/home/index', $data);
    }

    // ══════════════════════════════════════════════════════════
    //  QUẢN LÝ SÁCH
    // ══════════════════════════════════════════════════════════
    public function book(): void
    {
        $sachs    = $this->model->getAllSach();
        $theloais = $this->model->getAllTheloai();
        $nxbs     = $this->model->getAllNXB();
        $tacgias  = $this->model->getAllTacgia();
        $msg      = $_SESSION['admin_msg'] ?? '';
        unset($_SESSION['admin_msg']);
        $this->loadView('admin/book/index', compact('sachs','theloais','nxbs','tacgias','msg'));
    }

    public function addbook(): void
    {
        $theloais = $this->model->getAllTheloai();
        $nxbs     = $this->model->getAllNXB();
        $tacgias  = $this->model->getAllTacgia();
        $this->loadView('admin/book/form', compact('theloais','nxbs','tacgias'));
    }

    public function storebook(): void
    {
        $this->requirePost();
        $d        = $this->sanitizeBookPost();
        $hinhanh  = $this->uploadImage('hinhanh', 'sach');
        if ($hinhanh === false) { $_SESSION['admin_msg'] = 'Lỗi upload ảnh!'; $this->redirect('/admin/book'); return; }
        $this->model->insertSach($d, $hinhanh ?? '');
        $_SESSION['admin_msg'] = 'Thêm sách thành công!';
        $this->redirect('/admin/book');
    }

    public function editbook(string $id = '0'): void
    {
        $sach     = $this->model->getSachById((int)$id);
        if (!$sach) { $this->redirect('/admin/book'); return; }
        $theloais = $this->model->getAllTheloai();
        $nxbs     = $this->model->getAllNXB();
        $tacgias  = $this->model->getAllTacgia();
        $this->loadView('admin/book/form', compact('sach','theloais','nxbs','tacgias'));
    }

    public function updatebook(string $id = '0'): void
    {
        $this->requirePost();
        $idInt    = (int)$id;
        $d        = $this->sanitizeBookPost();
        $hinhanh  = $this->uploadImage('hinhanh', 'sach');
        if ($hinhanh === false) { $_SESSION['admin_msg'] = 'Lỗi upload ảnh!'; $this->redirect('/admin/book'); return; }
        // Xóa ảnh cũ nếu có ảnh mới
        if ($hinhanh !== null) {
            $old = $this->model->getSachById($idInt);
            if ($old && !empty($old['HinhAnh'])) {
                $oldPath = BASE_PATH . '/public/images/sach/' . $old['HinhAnh'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
        }
        $this->model->updateSach($idInt, $d, $hinhanh);
        $_SESSION['admin_msg'] = 'Cập nhật sách thành công!';
        $this->redirect('/admin/book');
    }

    public function deletebook(string $id = '0'): void
    {
        $hinhanh = $this->model->deleteSach((int)$id);
        if ($hinhanh) {
            $path = BASE_PATH . '/public/images/sach/' . $hinhanh;
            if (file_exists($path)) unlink($path);
        }
        $_SESSION['admin_msg'] = 'Đã xóa sách.';
        $this->redirect('/admin/book');
    }

    // ══════════════════════════════════════════════════════════
    //  QUẢN LÝ DANH MỤC
    // ══════════════════════════════════════════════════════════
    public function danhmuc(): void
    {
        $danhmucs = $this->model->getAllDanhmuc();
        $editItem = isset($_GET['edit']) ? $this->model->getDanhmucById((int)$_GET['edit']) : null;
        $msg      = $_SESSION['admin_msg'] ?? ''; unset($_SESSION['admin_msg']);
        $this->loadView('admin/danhmuc/index', compact('danhmucs','editItem','msg'));
    }

    public function storedanhmuc(): void
    {
        $this->requirePost();
        $ten = trim($_POST['tendanhmuc'] ?? '');
        if ($ten !== '') $this->model->insertDanhmuc($ten);
        $this->redirect('/admin/danhmuc');
    }

    public function updatedanhmuc(string $id = '0'): void
    {
        $this->requirePost();
        $ten = trim($_POST['tendanhmuc'] ?? '');
        if ($ten !== '') $this->model->updateDanhmuc((int)$id, $ten);
        $this->redirect('/admin/danhmuc');
    }

    public function deletedanhmuc(string $id = '0'): void
    {
        $this->model->deleteDanhmuc((int)$id);
        $this->redirect('/admin/danhmuc');
    }

    // ══════════════════════════════════════════════════════════
    //  QUẢN LÝ THỂ LOẠI
    // ══════════════════════════════════════════════════════════
    public function theloai(): void
    {
        $theloais = $this->model->getAllTheloai();
        $danhmucs = $this->model->getAllDanhmuc();
        $editItem = isset($_GET['edit']) ? $this->model->getTheloaiById((int)$_GET['edit']) : null;
        $msg      = $_SESSION['admin_msg'] ?? ''; unset($_SESSION['admin_msg']);
        $this->loadView('admin/theloai/index', compact('theloais','danhmucs','editItem','msg'));
    }

    public function storetheloai(): void
    {
        $this->requirePost();
        $ten  = trim($_POST['tentheloai'] ?? '');
        $idDm = (int)($_POST['iddanhmuc'] ?? 0);
        if ($ten !== '' && $idDm > 0) $this->model->insertTheloai($ten, $idDm);
        $this->redirect('/admin/theloai');
    }

    public function updatetheloai(string $id = '0'): void
    {
        $this->requirePost();
        $ten  = trim($_POST['tentheloai'] ?? '');
        $idDm = (int)($_POST['iddanhmuc'] ?? 0);
        if ($ten !== '' && $idDm > 0) $this->model->updateTheloai((int)$id, $ten, $idDm);
        $this->redirect('/admin/theloai');
    }

    public function deletetheloai(string $id = '0'): void
    {
        $this->model->deleteTheloai((int)$id);
        $this->redirect('/admin/theloai');
    }

    // ══════════════════════════════════════════════════════════
    //  QUẢN LÝ TÁC GIẢ
    // ══════════════════════════════════════════════════════════
    public function tacgia(): void
    {
        $tacgias  = $this->model->getAllTacgia();
        $editItem = isset($_GET['edit']) ? $this->model->getTacgiaById((int)$_GET['edit']) : null;
        $msg      = $_SESSION['admin_msg'] ?? ''; unset($_SESSION['admin_msg']);
        $this->loadView('admin/tacgia/index', compact('tacgias','editItem','msg'));
    }

    public function storetacgia(): void
    {
        $this->requirePost();
        $ten    = trim($_POST['tentacgia'] ?? '');
        $tieusu = trim($_POST['tieusu']    ?? '');
        if ($ten !== '') $this->model->insertTacgia($ten, $tieusu);
        $this->redirect('/admin/tacgia');
    }

    public function updatetacgia(string $id = '0'): void
    {
        $this->requirePost();
        $ten    = trim($_POST['tentacgia'] ?? '');
        $tieusu = trim($_POST['tieusu']    ?? '');
        if ($ten !== '') $this->model->updateTacgia((int)$id, $ten, $tieusu);
        $this->redirect('/admin/tacgia');
    }

    public function deletetacgia(string $id = '0'): void
    {
        $this->model->deleteTacgia((int)$id);
        $this->redirect('/admin/tacgia');
    }

    // ══════════════════════════════════════════════════════════
    //  QUẢN LÝ NHÀ XUẤT BẢN
    // ══════════════════════════════════════════════════════════
    public function nxb(): void
    {
        $nxbs     = $this->model->getAllNXB();
        $editItem = isset($_GET['edit']) ? $this->model->getNXBById((int)$_GET['edit']) : null;
        $msg      = $_SESSION['admin_msg'] ?? ''; unset($_SESSION['admin_msg']);
        $this->loadView('admin/nxb/index', compact('nxbs','editItem','msg'));
    }

    public function storenxb(): void
    {
        $this->requirePost();
        $ten    = trim($_POST['tennxb']    ?? '');
        $diachi = trim($_POST['diachi']    ?? '');
        $sdt    = trim($_POST['dienthoai'] ?? '');
        if ($ten !== '') $this->model->insertNXB($ten, $diachi, $sdt);
        $this->redirect('/admin/nxb');
    }

    public function updatenxb(string $id = '0'): void
    {
        $this->requirePost();
        $ten    = trim($_POST['tennxb']    ?? '');
        $diachi = trim($_POST['diachi']    ?? '');
        $sdt    = trim($_POST['dienthoai'] ?? '');
        if ($ten !== '') $this->model->updateNXB((int)$id, $ten, $diachi, $sdt);
        $this->redirect('/admin/nxb');
    }

    public function deletenxb(string $id = '0'): void
    {
        $this->model->deleteNXB((int)$id);
        $this->redirect('/admin/nxb');
    }

    // ══════════════════════════════════════════════════════════
    //  QUẢN LÝ ĐƠN HÀNG
    // ══════════════════════════════════════════════════════════
    public function donhang(): void
    {
        $donhangs = $this->model->getAllDonhang();
        $this->loadView('admin/donhang/index', compact('donhangs'));
    }

    /** AJAX: cập nhật trạng thái đơn hàng */
    public function updatedonhang(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo 'error'; return; }
        $id        = (int)($_POST['id']         ?? 0);
        $trangThai = (int)($_POST['trangthai']  ?? 0);
        if ($id > 0) {
            $this->model->updateTrangThaiDonhang($id, $trangThai);
            echo 'ok';
        } else {
            echo 'error';
        }
    }

    /** AJAX: load chi tiết sản phẩm trong đơn hàng */
    public function loadchitiet(): void
    {
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

    // ══════════════════════════════════════════════════════════
    //  QUẢN LÝ NGƯỜI DÙNG
    // ══════════════════════════════════════════════════════════
    public function nguoidung(): void
    {
        $users = $this->model->getAllNguoidung();
        $this->loadView('admin/nguoidung/index', compact('users'));
    }

    /** AJAX: cấp quyền Admin */
    public function capquyen(string $id = '0'): void
    {
        $this->model->setVaiTro((int)$id, 1);
        echo 'ok';
    }

    /** AJAX: hạ về quyền User */
    public function haquyen(string $id = '0'): void
    {
        // Không được tự hạ quyền chính mình
        if ((int)$id === (int)$_SESSION['IDNguoiDung']) { echo 'self'; return; }
        $this->model->setVaiTro((int)$id, 2);
        echo 'ok';
    }

    // ══════════════════════════════════════════════════════════
    //  QUẢN LÝ ĐÁNH GIÁ
    // ══════════════════════════════════════════════════════════
    public function danhgia(): void
    {
        $danhgias = $this->model->getAllDanhgia();
        $msg      = $_SESSION['admin_msg'] ?? ''; unset($_SESSION['admin_msg']);
        $this->loadView('admin/danhgia/index', compact('danhgias','msg'));
    }

    public function deletedanhgia(string $id = '0'): void
    {
        $this->model->deleteDanhgia((int)$id);
        $_SESSION['admin_msg'] = 'Đã xóa đánh giá.';
        $this->redirect('/admin/danhgia');
    }

    // ══════════════════════════════════════════════════════════
    //  Helpers
    // ══════════════════════════════════════════════════════════

    /** Yêu cầu đăng nhập và là Admin (IDVaiTro = 1) */
    private function requireAdmin(): void
    {
        if (!isset($_SESSION['IDNguoiDung']) || (int)($_SESSION['IDVaiTro'] ?? 0) !== 1) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit();
        }
    }

    private function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin');
        }
    }

    private function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit();
    }

    /** Sanitize POST data cho sách */
    private function sanitizeBookPost(): array
    {
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

    /**
     * Upload hình ảnh, trả về tên file mới | null (không upload) | false (lỗi)
     */
    private function uploadImage(string $field, string $subdir): string|null|false
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // Không có file — giữ ảnh cũ
        }
        if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) return false;

        $origName = $_FILES[$field]['name'];
        $tmpName  = $_FILES[$field]['tmp_name'];
        $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return false;

        $newName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $origName);
        $destDir = BASE_PATH . '/public/images/' . $subdir . '/';

        if (!is_dir($destDir)) mkdir($destDir, 0755, true);
        if (!move_uploaded_file($tmpName, $destDir . $newName)) return false;

        return $newName;
    }

    private function loadView(string $view, array $data = []): void
    {
        extract($data);
        $layoutFile = BASE_PATH . '/view/admin/layout.php';
        $viewFile   = BASE_PATH . '/view/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("<h2>Admin View không tồn tại: <code>{$view}.php</code></h2>");
        }
        require $layoutFile;
    }
}
