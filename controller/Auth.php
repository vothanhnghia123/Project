<?php
// ============================================================
//  controller/Auth.php
//  URL map (route /auth/*):
//    GET/POST /auth/login          → login()
//    GET/POST /auth/register       → register()
//    GET      /auth/logout         → logout()
//    GET/POST /auth/profile        → profile()
//    POST     /auth/changepassword → changepassword()
//
//  header.php dùng /home/login, /home/register, v.v.
//  → Thêm alias trong controller/Home.php (xem bên dưới)
// ============================================================

require_once BASE_PATH . '/model/Auth.php';
require_once BASE_PATH . '/model/News.php';

class Auth
{
    private AuthModel $authModel;
    private News  $newsModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
        $this->newsModel = new News();
    }

    // ── ĐĂNG NHẬP ────────────────────────────────────────────
    public function login(): void
    {
        if (isset($_SESSION['IDNguoiDung'])) {
            header('Location: ' . BASE_URL . '/'); exit();
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']   ?? '');
            $password = trim($_POST['matkhau'] ?? '');

            if ($email === '' || $password === '') {
                $error = 'Vui lòng điền đầy đủ thông tin.';
            } else {
                $user = $this->authModel->login($email, $password);

                if ($user) {
                    $_SESSION['IDNguoiDung'] = $user['IDNguoiDung'];
                    $_SESSION['HoTen']       = $user['HoTen'];
                    $_SESSION['IDVaiTro']    = $user['IDVaiTro'];

                    if ((int)$user['IDVaiTro'] === 1) {
                        header('Location: ' . BASE_URL . '/admin');
                    } else {
                        header('Location: ' . BASE_URL . '/');
                    }
                    exit();
                } else {
                    $error = 'Sai email hoặc mật khẩu.';
                }
            }
        }

        $categories = $this->getCategories();
        $this->loadView('auth/login', compact('error', 'categories'));
    }

    // ── ĐĂNG KÝ ─────────────────────────────────────────────
    public function register(): void
    {
        if (isset($_SESSION['IDNguoiDung'])) {
            header('Location: ' . BASE_URL . '/'); exit();
        }

        $error = $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoTen    = trim($_POST['hoten']   ?? '');
            $email    = trim($_POST['email']   ?? '');
            $password = trim($_POST['matkhau'] ?? '');
            $repass   = trim($_POST['repass']  ?? '');

            if ($hoTen === '' || $email === '' || $password === '') {
                $error = 'Vui lòng điền đầy đủ thông tin.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email không hợp lệ.';
            } elseif (strlen($password) < 6) {
                $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
            } elseif ($password !== $repass) {
                $error = 'Mật khẩu nhập lại không khớp.';
            } elseif ($this->authModel->emailExists($email)) {
                $error = 'Email này đã được sử dụng.';
            } else {
                if ($this->authModel->register($hoTen, $email, $password)) {
                    $success = 'Đăng ký thành công! Hãy đăng nhập.';
                } else {
                    $error = 'Có lỗi xảy ra, vui lòng thử lại.';
                }
            }
        }

        $categories = $this->getCategories();
        $this->loadView('auth/register', compact('error', 'success', 'categories'));
    }

    // ── ĐĂNG XUẤT ────────────────────────────────────────────
    public function logout(): void
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit();
    }

    // ── PROFILE ──────────────────────────────────────────────
    public function profile(): void
    {
        $this->requireLogin();

        $id    = (int)$_SESSION['IDNguoiDung'];
        $user  = $this->authModel->getUserById($id);
        $page  = $_GET['tab'] ?? 'info';
        $msgOk = $msgErr = '';

        // Cập nhật thông tin cá nhân
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['luu'])) {
            $hoTen     = trim($_POST['hoten']     ?? '');
            $email     = trim($_POST['email']     ?? '');
            $dienThoai = trim($_POST['dienthoai'] ?? '');
            $diaChi    = trim($_POST['diachi']    ?? '');

            if ($hoTen === '' || $email === '') {
                $msgErr = 'Họ tên và Email không được để trống.';
            } else {
                $this->authModel->updateProfile($id, $hoTen, $email, $dienThoai, $diaChi);
                $_SESSION['HoTen'] = $hoTen;
                $user  = $this->authModel->getUserById($id);
                $msgOk = 'Cập nhật thông tin thành công.';
            }
            $page = 'info';
        }

        // Đổi mật khẩu
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doimatkhau'])) {
            $oldPass = $_POST['oldpass']   ?? '';
            $newPass = $_POST['newpass']   ?? '';
            $rePass  = $_POST['renewpass'] ?? '';

            if ($oldPass === '' || $newPass === '' || $rePass === '') {
                $msgErr = 'Vui lòng điền đầy đủ các trường mật khẩu.';
            } elseif (!$this->authModel->checkPassword($id, $oldPass)) {
                $msgErr = 'Mật khẩu cũ không đúng.';
            } elseif (strlen($newPass) < 6) {
                $msgErr = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
            } elseif ($newPass !== $rePass) {
                $msgErr = 'Mật khẩu nhập lại không khớp.';
            } else {
                $this->authModel->changePassword($id, $newPass);
                $msgOk = 'Đổi mật khẩu thành công.';
            }
            $page = 'password';
        }

        $orders = ($page === 'orders')
            ? $this->authModel->getOrdersByUser($id)
            : [];

        $categories = $this->getCategories();
        $this->loadView('auth/profile', compact('user', 'page', 'orders', 'msgOk', 'msgErr', 'categories'));
    }

    // ── Helpers ──────────────────────────────────────────────
    private function requireLogin(): void
    {
        if (!isset($_SESSION['IDNguoiDung'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit();
        }
    }

    private function getCategories(): array
    {
        $list = $this->newsModel->getAllDanhmuc();
        foreach ($list as &$dm) {
            $dm['theloai'] = $this->newsModel->getTheloaiByDanhmuc((int)$dm['IDDanhMuc']);
        }
        unset($dm);
        return $list;
    }

    private function loadView(string $view, array $data = []): void
    {
        extract($data);
        $viewFile   = BASE_PATH . '/view/' . $view . '.php';
        $layoutFile = BASE_PATH . '/view/auth/layout.php';

        if (!file_exists($viewFile)) {
            die("<h2>View không tồn tại: <code>{$view}.php</code></h2>");
        }
        require $layoutFile;
    }
}
