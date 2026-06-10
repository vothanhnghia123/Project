<?php
require_once "model/ModelBook.php";
require_once "model/ModelNews.php";

class Book {
    private $bookModel;
    private $newsModel;
    private $perPage = 8;

    public function __construct() {
        $this->bookModel = new ModelBook();
        $this->newsModel = new ModelNews();
    }

    // ── Tất cả sách ────────────────────────────────────────────
    public function index() {
        $total       = $this->bookModel->countBooks();
        list($currentPage, $totalPage, $offset) = $this->paginate($total);

        $books          = $this->bookModel->getBooksPaginated($offset, $this->perPage);
        $title          = 'Tất cả sách';
        $filterType     = null;
        $filterId       = null;
        $danhmucs       = $this->newsModel->getAllDanhmuc();
        $categories     = $this->buildCategories($danhmucs);
        $suggestedBooks = $this->bookModel->getRandomBooks(10);

        require_once "view/book/list.php";
    }

    // ── Lọc theo danh mục ──────────────────────────────────────
    public function danhmuc() {
        $idDanhmuc  = max(1, (int)($_GET['param'] ?? 0));
        $dmInfo     = $this->newsModel->getDanhmucById($idDanhmuc);
        $title      = $dmInfo ? $dmInfo['TenDanhMuc'] : 'Danh mục';

        $total      = $this->bookModel->countBooks($idDanhmuc, null);
        list($currentPage, $totalPage, $offset) = $this->paginate($total);

        $books          = $this->bookModel->getBooksPaginated($offset, $this->perPage, $idDanhmuc, null);
        $danhmucs       = $this->newsModel->getAllDanhmuc();
        $categories     = $this->buildCategories($danhmucs);
        $filterType     = 'danhmuc';
        $filterId       = $idDanhmuc;
        $suggestedBooks = $this->bookModel->getRandomBooks(10);

        require_once "view/book/list.php";
    }

    // ── Lọc theo thể loại ──────────────────────────────────────
    public function theloai() {
        $idTheloai = max(1, (int)($_GET['param'] ?? 0));
        $tlInfo    = $this->newsModel->getTheloaiById($idTheloai);
        $title     = $tlInfo ? $tlInfo['TenTheLoai'] : 'Thể loại';

        $total     = $this->bookModel->countBooks(null, $idTheloai);
        list($currentPage, $totalPage, $offset) = $this->paginate($total);

        $books          = $this->bookModel->getBooksPaginated($offset, $this->perPage, null, $idTheloai);
        $danhmucs       = $this->newsModel->getAllDanhmuc();
        $categories     = $this->buildCategories($danhmucs);
        $filterType     = 'theloai';
        $filterId       = $idTheloai;
        $suggestedBooks = $this->bookModel->getRandomBooks(10);

        require_once "view/book/list.php";
    }

    // ── Chi tiết sách ──────────────────────────────────────────
    public function detail() {
        $idSach = max(1, (int)($_GET['param'] ?? 0));
        $book   = $this->bookModel->getBookDetail($idSach);

        if (!$book) {
            die('<h2>404 – Không tìm thấy sách.</h2>');
        }

        $rating   = $this->bookModel->getRatingSummary($idSach);
        $reviews  = $this->bookModel->getReviews($idSach, 5);
        $danhmucs = $this->newsModel->getAllDanhmuc();
        $categories = $this->buildCategories($danhmucs);

        $tong = (int)($rating['tong'] ?? 0);
        $pt   = [];
        for ($s = 1; $s <= 5; $s++) {
            $pt[$s] = $tong > 0 ? round(($rating["sao{$s}"] ?? 0) / $tong * 100) : 0;
        }

        require_once "view/book/detail.php";
    }

    // ── Giỏ hàng ───────────────────────────────────────────────
    public function cart() {
        $ids        = $_SESSION['id_them_vao_gio'] ?? [];
        $quantities = $_SESSION['sl_them_vao_gio'] ?? [];
        $books      = $this->bookModel->getBooksByIds($ids);
        $danhmucs   = $this->newsModel->getAllDanhmuc();
        $categories = $this->buildCategories($danhmucs);

        $cartItems = [];
        $tongCong  = 0;
        foreach ($ids as $i => $idSach) {
            if (!isset($books[$idSach])) continue;
            $sl         = (int)($quantities[$i] ?? 1);
            $tien       = $books[$idSach]['GiaBan'] * $sl;
            $tongCong  += $tien;
            $cartItems[] = [
                'vitri'     => $i,
                'IDSach'    => $idSach,
                'TenSach'   => $books[$idSach]['TenSach'],
                'HinhAnh'   => $books[$idSach]['HinhAnh'],
                'GiaBan'    => $books[$idSach]['GiaBan'],
                'SoLuong'   => $sl,
                'ThanhTien' => $tien,
            ];
        }

        $thieuThongTin = false;
        if (isset($_SESSION['IDNguoiDung'])) {
            $user = $this->newsModel->getUserById((int)$_SESSION['IDNguoiDung']);
            if (empty($user['DienThoai']) || empty($user['DiaChi'])) {
                $thieuThongTin = true;
            }
        }

        require_once "view/book/cart.php";
    }

    // ── AJAX thêm vào giỏ ─────────────────────────────────────
    public function addcart() {
        $id = (int)($_GET['id']       ?? 0);
        $sl = (int)($_GET['so_luong'] ?? 1);

        if ($id <= 0 || $sl <= 0) { echo 0; return; }

        if (!isset($_SESSION['id_them_vao_gio'])) {
            $_SESSION['id_them_vao_gio'] = [];
            $_SESSION['sl_them_vao_gio'] = [];
        }

        $found = false;
        foreach ($_SESSION['id_them_vao_gio'] as $i => $existing) {
            if ((int)$existing === $id) {
                $_SESSION['sl_them_vao_gio'][$i] += $sl;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['id_them_vao_gio'][] = $id;
            $_SESSION['sl_them_vao_gio'][] = $sl;
        }

        echo array_sum($_SESSION['sl_them_vao_gio']);
    }

    // ── Xóa 1 sản phẩm ─────────────────────────────────────────
    public function removecart() {
        $vitri = (int)($_GET['vi'] ?? -1);
        if (isset($_SESSION['id_them_vao_gio'][$vitri])) {
            array_splice($_SESSION['id_them_vao_gio'], $vitri, 1);
            array_splice($_SESSION['sl_them_vao_gio'], $vitri, 1);
        }
        header('Location: index.php?controller=Book&action=cart');
        exit();
    }

    // ── Xóa toàn bộ giỏ ────────────────────────────────────────
    public function clearcart() {
        unset($_SESSION['id_them_vao_gio'], $_SESSION['sl_them_vao_gio']);
        header('Location: index.php?controller=Book&action=cart');
        exit();
    }

    // ── Tăng/giảm số lượng ─────────────────────────────────────
    public function qty() {
        $vitri  = (int)($_GET['vi'] ?? -1);
        $action = trim($_GET['do']  ?? '');

        if (!isset($_SESSION['sl_them_vao_gio'][$vitri])) {
            header('Location: index.php?controller=Book&action=cart');
            exit();
        }

        if ($action === 'tang') {
            $_SESSION['sl_them_vao_gio'][$vitri]++;
        } elseif ($action === 'giam') {
            $_SESSION['sl_them_vao_gio'][$vitri]--;
            if ($_SESSION['sl_them_vao_gio'][$vitri] <= 0) {
                array_splice($_SESSION['id_them_vao_gio'], $vitri, 1);
                array_splice($_SESSION['sl_them_vao_gio'], $vitri, 1);
            }
        }

        header('Location: index.php?controller=Book&action=cart');
        exit();
    }

    // ── Đặt hàng ───────────────────────────────────────────────
    public function order() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=Book&action=cart');
            exit();
        }

        if (!isset($_SESSION['IDNguoiDung'])) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }

        if (empty($_SESSION['id_them_vao_gio'])) {
            header('Location: index.php?controller=Book&action=cart');
            exit();
        }

        $idNguoiDung  = (int)$_SESSION['IDNguoiDung'];
        $phuongThucTT = in_array($_POST['thanhtoan'] ?? '', ['COD', 'ATM'])
                        ? $_POST['thanhtoan'] : 'COD';

        if (!empty($_POST['sdt']) || !empty($_POST['diachi'])) {
            $user = $this->newsModel->getUserById($idNguoiDung);
            $this->newsModel->updateUserInfo(
                $idNguoiDung,
                $user['HoTen'],
                $user['Email'],
                trim($_POST['sdt']    ?? $user['DienThoai']),
                trim($_POST['diachi'] ?? $user['DiaChi'])
            );
        }

        $result = $this->bookModel->placeOrder(
            $idNguoiDung,
            $_SESSION['id_them_vao_gio'],
            $_SESSION['sl_them_vao_gio'],
            $phuongThucTT
        );

        if ($result !== false) {
            unset($_SESSION['id_them_vao_gio'], $_SESSION['sl_them_vao_gio']);
            header('Location: index.php?controller=Book&action=cart&success=1');
        } else {
            header('Location: index.php?controller=Book&action=cart&error=1');
        }
        exit();
    }

    // ── Gửi đánh giá ───────────────────────────────────────────
    public function review() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit();
        }

        if (!isset($_SESSION['IDNguoiDung'])) {
            header('Location: index.php?controller=User&action=login');
            exit();
        }

        $idSach      = (int)($_POST['idsach']  ?? 0);
        $soSao       = max(1, min(5, (int)($_POST['sosao'] ?? 5)));
        $noiDung     = trim($_POST['noidung']  ?? '');
        $idNguoiDung = (int)$_SESSION['IDNguoiDung'];

        if ($idSach > 0 && $noiDung !== '') {
            $this->bookModel->saveReview($idSach, $idNguoiDung, $soSao, $noiDung);
        }

        header('Location: index.php?controller=Book&action=detail&param=' . $idSach);
        exit();
    }

    // ── Helpers ────────────────────────────────────────────────
    private function paginate($total) {
        $totalPage   = max(1, (int)ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPage, (int)($_GET['page'] ?? 1)));
        $offset      = ($currentPage - 1) * $this->perPage;
        return [$currentPage, $totalPage, $offset];
    }

    private function buildCategories($danhmucs) {
        $list = [];
        foreach ($danhmucs as $dm) {
            $dm['theloai'] = $this->newsModel->getTheloaiByDanhmuc((int)$dm['IDDanhMuc']);
            $list[] = $dm;
        }
        return $list;
    }
}
