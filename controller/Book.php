<?php
/**
 * controller/Book.php
 * ─────────────────────────────────────────────────────────────
 * URL map (router dùng strtolower cho action):
 *   GET  /book                     → index()
 *   GET  /book/danhmuc/{id}        → danhmuc($id)
 *   GET  /book/theloai/{id}        → theloai($id)
 *   GET  /book/detail/{id}         → detail($id)
 *   GET  /book/cart                → cart()
 *   GET  /book/addcart?id=&so_luong= → addcart()   ← AJAX, trả số nguyên
 *   GET  /book/removecart?vi=      → removecart()
 *   GET  /book/clearcart           → clearcart()
 *   GET  /book/qty?vi=&action=     → qty()
 *   POST /book/order               → order()
 *   POST /book/review              → review()
 *
 * Biến truyền sang View:
 *   - Tất cả view dùng layout home đều nhận $categories + $viewFile
 *   - $danhmucs (flat) được layout.php tự bổ sung 'theloai' nếu cần
 *   - Để thống nhất: controller này truyền $categories (đầy đủ) thay
 *     vì $danhmucs để layout không phải query thêm DB
 */

require_once BASE_PATH . '/model/Book.php';   // class BookModel
require_once BASE_PATH . '/model/News.php';   // class News

class Book
{
    private BookModel $bookModel;
    private News      $newsModel;
    private int       $perPage = 8;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->newsModel = new News();
    }

    // ──────────────────────────────────────────────────────────
    //  DANH SÁCH TẤT CẢ SÁCH (phân trang)
    // ──────────────────────────────────────────────────────────
    public function index(): void
    {
        [$currentPage, $totalPage, $offset] = $this->paginate(
            $this->bookModel->countBooks()
        );

        $books      = $this->bookModel->getBooksPaginated($offset, $this->perPage);
        $title      = 'Tất cả sách';
        $filterType = null;
        $filterId   = null;

        // Sidebar cần danhmucs (flat); layout tự bổ sung theloai
        $danhmucs   = $this->newsModel->getAllDanhmuc();
        // Header cần categories (có theloai lồng trong)
        $categories = $this->buildCategories($danhmucs);

        $suggestedBooks = $this->bookModel->getRandomBooks(10);

        $this->loadView('book/list', compact(
            'books', 'danhmucs', 'categories', 'title',
            'currentPage', 'totalPage', 'filterType', 'filterId',
            'suggestedBooks'
        ));
    }

    // ──────────────────────────────────────────────────────────
    //  LỌC THEO DANH MỤC
    // ──────────────────────────────────────────────────────────
    public function danhmuc(string $id = '0'): void
    {
        $idDanhmuc = max(1, (int)$id);
        $dmInfo    = $this->newsModel->getDanhmucById($idDanhmuc);
        $title     = $dmInfo ? $dmInfo['TenDanhMuc'] : 'Danh mục';

        [$currentPage, $totalPage, $offset] = $this->paginate(
            $this->bookModel->countBooks($idDanhmuc, null)
        );

        $books          = $this->bookModel->getBooksPaginated($offset, $this->perPage, $idDanhmuc, null);
        $danhmucs       = $this->newsModel->getAllDanhmuc();
        $categories     = $this->buildCategories($danhmucs);
        $filterType     = 'danhmuc';
        $filterId       = $idDanhmuc;
        $suggestedBooks = $this->bookModel->getRandomBooks(10);

        $this->loadView('book/list', compact(
            'books', 'danhmucs', 'categories', 'title',
            'currentPage', 'totalPage', 'filterType', 'filterId',
            'suggestedBooks'
        ));
    }

    // ──────────────────────────────────────────────────────────
    //  LỌC THEO THỂ LOẠI
    // ──────────────────────────────────────────────────────────
    public function theloai(string $id = '0'): void
    {
        $idTheloai = max(1, (int)$id);
        $tlInfo    = $this->newsModel->getTheloaiById($idTheloai);
        $title     = $tlInfo ? $tlInfo['TenTheLoai'] : 'Thể loại';

        [$currentPage, $totalPage, $offset] = $this->paginate(
            $this->bookModel->countBooks(null, $idTheloai)
        );

        $books          = $this->bookModel->getBooksPaginated($offset, $this->perPage, null, $idTheloai);
        $danhmucs       = $this->newsModel->getAllDanhmuc();
        $categories     = $this->buildCategories($danhmucs);
        $filterType     = 'theloai';
        $filterId       = $idTheloai;
        $suggestedBooks = $this->bookModel->getRandomBooks(10);

        $this->loadView('book/list', compact(
            'books', 'danhmucs', 'categories', 'title',
            'currentPage', 'totalPage', 'filterType', 'filterId',
            'suggestedBooks'
        ));
    }

    // ──────────────────────────────────────────────────────────
    //  CHI TIẾT SÁCH
    // ──────────────────────────────────────────────────────────
    public function detail(string $id = '0'): void
    {
        $idSach = max(1, (int)$id);
        $book   = $this->bookModel->getBookDetail($idSach);

        if (!$book) {
            http_response_code(404);
            die('<h2 style="font-family:sans-serif">404 – Không tìm thấy sách.</h2>');
        }

        $rating   = $this->bookModel->getRatingSummary($idSach);
        $reviews  = $this->bookModel->getReviews($idSach, 5);
        $danhmucs = $this->newsModel->getAllDanhmuc();
        $categories = $this->buildCategories($danhmucs);

        // Phần trăm từng mức sao
        $tong = (int)($rating['tong'] ?? 0);
        $pt   = [];
        for ($s = 1; $s <= 5; $s++) {
            $pt[$s] = $tong > 0 ? round(($rating["sao{$s}"] ?? 0) / $tong * 100) : 0;
        }

        $this->loadView('book/detail', compact(
            'book', 'rating', 'reviews', 'danhmucs', 'categories', 'pt', 'tong'
        ));
    }

    // ──────────────────────────────────────────────────────────
    //  GIỎ HÀNG
    // ──────────────────────────────────────────────────────────
    public function cart(): void
    {
        $ids        = $_SESSION['id_them_vao_gio']  ?? [];
        $quantities = $_SESSION['sl_them_vao_gio']  ?? [];
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

        // Thiếu SĐT / địa chỉ → hiện thêm form nhập trong trang giỏ
        $thieuThongTin = false;
        if (isset($_SESSION['IDNguoiDung'])) {
            $user = $this->newsModel->getUserById((int)$_SESSION['IDNguoiDung']);
            if (empty($user['DienThoai']) || empty($user['DiaChi'])) {
                $thieuThongTin = true;
            }
        }

        $this->loadView('book/cart', compact(
            'cartItems', 'tongCong', 'thieuThongTin', 'danhmucs', 'categories'
        ));
    }

    // ──────────────────────────────────────────────────────────
    //  AJAX THÊM VÀO GIỎ
    //  URL: GET /book/addcart?id={IDSach}&so_luong={n}
    //  Trả về: số nguyên (tổng số lượng trong giỏ)
    // ──────────────────────────────────────────────────────────
    public function addcart(): void
    {
        $id = (int)($_GET['id']       ?? 0);
        $sl = (int)($_GET['so_luong'] ?? 1);

        if ($id <= 0 || $sl <= 0) { echo 0; return; }

        if (!isset($_SESSION['id_them_vao_gio'])) {
            $_SESSION['id_them_vao_gio'] = [];
            $_SESSION['sl_them_vao_gio'] = [];
        }

        // Nếu đã có sách này → cộng thêm số lượng
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

        // Trả về tổng số lượng (cập nhật badge giỏ hàng)
        echo array_sum($_SESSION['sl_them_vao_gio']);
    }

    // ──────────────────────────────────────────────────────────
    //  XÓA 1 SẢN PHẨM  →  GET /book/removecart?vi={index}
    // ──────────────────────────────────────────────────────────
    public function removecart(): void
    {
        $vitri = (int)($_GET['vi'] ?? -1);
        if (isset($_SESSION['id_them_vao_gio'][$vitri])) {
            array_splice($_SESSION['id_them_vao_gio'], $vitri, 1);
            array_splice($_SESSION['sl_them_vao_gio'], $vitri, 1);
        }
        header('Location: ' . BASE_URL . '/book/cart');
        exit();
    }

    // ──────────────────────────────────────────────────────────
    //  XÓA TOÀN BỘ GIỎ  →  GET /book/clearcart
    // ──────────────────────────────────────────────────────────
    public function clearcart(): void
    {
        unset($_SESSION['id_them_vao_gio'], $_SESSION['sl_them_vao_gio']);
        header('Location: ' . BASE_URL . '/book/cart');
        exit();
    }

    // ──────────────────────────────────────────────────────────
    //  TĂNG / GIẢM SỐ LƯỢNG  →  GET /book/qty?vi=&action=tang|giam
    // ──────────────────────────────────────────────────────────
    public function qty(): void
    {
        $vitri  = (int)($_GET['vi']     ?? -1);
        $action = trim($_GET['action']  ?? '');

        if (!isset($_SESSION['sl_them_vao_gio'][$vitri])) {
            header('Location: ' . BASE_URL . '/book/cart');
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

        header('Location: ' . BASE_URL . '/book/cart');
        exit();
    }

    // ──────────────────────────────────────────────────────────
    //  ĐẶT HÀNG  →  POST /book/order
    // ──────────────────────────────────────────────────────────
    public function order(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/book/cart');
            exit();
        }

        if (!isset($_SESSION['IDNguoiDung'])) {
            header('Location: ' . BASE_URL . '/home/login');
            exit();
        }

        if (empty($_SESSION['id_them_vao_gio'])) {
            header('Location: ' . BASE_URL . '/book/cart');
            exit();
        }

        $idNguoiDung  = (int)$_SESSION['IDNguoiDung'];
        $phuongThucTT = in_array($_POST['thanhtoan'] ?? '', ['COD', 'ATM'])
                        ? $_POST['thanhtoan'] : 'COD';

        // Lưu SĐT / địa chỉ nếu user vừa nhập trong form giỏ hàng
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
            header('Location: ' . BASE_URL . '/book/cart?success=1');
        } else {
            header('Location: ' . BASE_URL . '/book/cart?error=1');
        }
        exit();
    }

    // ──────────────────────────────────────────────────────────
    //  GỬI ĐÁNH GIÁ  →  POST /book/review
    // ──────────────────────────────────────────────────────────
    public function review(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/');
            exit();
        }

        if (!isset($_SESSION['IDNguoiDung'])) {
            header('Location: ' . BASE_URL . '/home/login');
            exit();
        }

        $idSach      = (int)($_POST['idsach']  ?? 0);
        $soSao       = max(1, min(5, (int)($_POST['sosao'] ?? 5)));
        $noiDung     = trim($_POST['noidung']  ?? '');
        $idNguoiDung = (int)$_SESSION['IDNguoiDung'];

        if ($idSach > 0 && $noiDung !== '') {
            $this->bookModel->saveReview($idSach, $idNguoiDung, $soSao, $noiDung);
        }

        header('Location: ' . BASE_URL . '/book/detail/' . $idSach);
        exit();
    }

    // ──────────────────────────────────────────────────────────
    //  Helper: tính phân trang
    //  Trả về [$currentPage, $totalPage, $offset]
    // ──────────────────────────────────────────────────────────
    private function paginate(int $total): array
    {
        $totalPage   = max(1, (int)ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPage, (int)($_GET['page'] ?? 1)));
        $offset      = ($currentPage - 1) * $this->perPage;
        return [$currentPage, $totalPage, $offset];
    }

    // ──────────────────────────────────────────────────────────
    //  Helper: build $categories cho mega-menu (có theloai lồng)
    //  Nhận $danhmucs (flat) từ nơi đã query để tránh query lại
    // ──────────────────────────────────────────────────────────
    private function buildCategories(array $danhmucs): array
    {
        $list = [];
        foreach ($danhmucs as $dm) {
            $dm['theloai'] = $this->newsModel->getTheloaiByDanhmuc(
                (int)$dm['IDDanhMuc']
            );
            $list[] = $dm;
        }
        return $list;
    }

    // ──────────────────────────────────────────────────────────
    //  Helper: load view qua home layout
    //  $data phải chứa $categories (đã có theloai lồng trong)
    // ──────────────────────────────────────────────────────────
    private function loadView(string $view, array $data = []): void
    {
        extract($data);
        $viewFile   = BASE_PATH . '/view/' . $view . '.php';
        $layoutFile = BASE_PATH . '/view/home/layout.php';

        if (!file_exists($viewFile)) {
            die("<h2 style='font-family:sans-serif'>View không tồn tại: <code>{$view}.php</code></h2>");
        }
        require $layoutFile;
    }
}