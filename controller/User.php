<?php
require_once "model/ModelUser.php";
require_once "model/ModelNews.php";

class User {
    private $userModel;
    private $newsModel;

    public function __construct() {
        $this->userModel = new ModelUser();
        $this->newsModel = new ModelNews();
    }

    // ── Đăng nhập ──────────────────────────────────────────────
    public function login() {
        if (isset($_SESSION['IDNguoiDung'])) {
            header('Location: index.php');
            exit();
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']   ?? '');
            $password = trim($_POST['matkhau'] ?? '');

            if ($email === '' || $password === '') {
                $error = 'Vui lòng điền đầy đủ thông tin.';
            } else {
                $user = $this->userModel->login($email, $password);

                if ($user) {
                    $_SESSION['IDNguoiDung'] = $user['IDNguoiDung'];
                    $_SESSION['HoTen']       = $user['HoTen'];
                    $_SESSION['IDVaiTro']    = $user['IDVaiTro'];

                    if ((int)$user['IDVaiTro'] === 1) {
                        header('Location: index.php?controller=AdminHome&action=index');
                    } else {
                        header('Location: index.php');
                    }
                    exit();
                } else {
                    $error = 'Sai email hoặc mật khẩu.';
                }
            }
        }

        $categories = $this->buildCategories();
        require_once "view/user/login.php";
    }

    // ── Đăng ký ────────────────────────────────────────────────
    public function register() {
        if (isset($_SESSION['IDNguoiDung'])) {
            header('Location: index.php');
            exit();
        }

        $error   = '';
        $success = '';

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
            } elseif ($this->userModel->emailExists($email)) {
                $error = 'Email này đã được sử dụng.';
            } else {
                if ($this->userModel->register($hoTen, $email, $password)) {
                    $success = 'Đăng ký thành công! Hãy đăng nhập.';
                } else {
                    $error = 'Có lỗi xảy ra, vui lòng thử lại.';
                }
            }
        }

        $categories = $this->buildCategories();
        require_once "view/user/register.php";
    }

    // ── Đăng xuất ──────────────────────────────────────────────
    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit();
    }

    // ── Trang cá nhân ──────────────────────────────────────────
    public function profile() {
        if (!isset($_SESSION['IDNguoiDung'])) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }

        $id    = (int)$_SESSION['IDNguoiDung'];
        $user  = $this->userModel->getUserById($id);
        $page  = $_GET['tab'] ?? 'info';
        $msgOk = '';
        $msgErr = '';

        // Cập nhật thông tin cá nhân
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['luu'])) {
            $hoTen     = trim($_POST['hoten']     ?? '');
            $email     = trim($_POST['email']     ?? '');
            $dienThoai = trim($_POST['dienthoai'] ?? '');
            $diaChi    = trim($_POST['diachi']    ?? '');

            if ($hoTen === '' || $email === '') {
                $msgErr = 'Họ tên và Email không được để trống.';
            } else {
                $this->userModel->updateProfile($id, $hoTen, $email, $dienThoai, $diaChi);
                $_SESSION['HoTen'] = $hoTen;
                $user   = $this->userModel->getUserById($id);
                $msgOk  = 'Cập nhật thông tin thành công.';
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
            } elseif (!$this->userModel->checkPassword($id, $oldPass)) {
                $msgErr = 'Mật khẩu cũ không đúng.';
            } elseif (strlen($newPass) < 6) {
                $msgErr = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
            } elseif ($newPass !== $rePass) {
                $msgErr = 'Mật khẩu nhập lại không khớp.';
            } else {
                $this->userModel->changePassword($id, $newPass);
                $msgOk = 'Đổi mật khẩu thành công.';
            }
            $page = 'password';
        }

        $orders = ($page === 'orders')
            ? $this->userModel->getOrdersByUser($id)
            : [];

        $categories = $this->buildCategories();
        require_once "view/user/profile.php";
    }

    // ── Helper ─────────────────────────────────────────────────
    private function buildCategories() {
        $list = $this->newsModel->getAllDanhmuc();
        foreach ($list as &$dm) {
            $dm['theloai'] = $this->newsModel->getTheloaiByDanhmuc((int)$dm['IDDanhMuc']);
        }
        unset($dm);
        return $list;
    }
}
