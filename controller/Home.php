<?php
/**
 * controller/Home.php
 * ─────────────────────────────────────────────────────────────
 * URL map:
 *   GET /                     → index()       trang chủ
 *   GET /home/search          → search()      kết quả tìm kiếm
 *   GET /home/livesearch      → livesearch()  AJAX gợi ý (chú ý: lowercase)
 *   GET /home/gioithieu       → gioithieu()   giới thiệu
 *   GET/POST /home/login      → login()       alias → Auth::login
 *   GET/POST /home/register   → register()    alias → Auth::register
 *   GET /home/logout          → logout()      alias → Auth::logout
 *   GET/POST /home/profile    → profile()     alias → Auth::profile
 *
 * Chú ý router (index.php):
 *   $actionName = strtolower($segments[1])
 *   → /home/liveSearch → action = 'livesearch' (toàn chữ thường)
 *   → method phải đặt tên là livesearch() KHÔNG phải liveSearch()
 */

require_once BASE_PATH . '/model/Book.php';   // class BookModel

class Home
{
    private BookModel $bookModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
    }

    // ──────────────────────────────────────────────────────────
    //  TRANG CHỦ
    // ──────────────────────────────────────────────────────────
    public function index(): void
    {
        $newBooks       = $this->bookModel->getNewBooks(8);
        $vanhocBooks    = $this->bookModel->getBooksByCategory('Văn học', 10);
        $vanhocDMId     = $this->bookModel->getCategoryIdByName('Văn học');
        $thieunhiBooks  = $this->bookModel->getBooksByCategory('Thiếu nhi', 10);
        $thieunhiDMId   = $this->bookModel->getCategoryIdByName('Thiếu nhi');
        $suggestedBooks = $this->bookModel->getRandomBooks(10);

        // $categories = mảng danh mục có lồng 'theloai' → header.php dùng
        $categories = $this->buildCategories();

        $viewFile = BASE_PATH . '/view/home/index.php';
        require BASE_PATH . '/view/home/layout.php';
    }

    // ──────────────────────────────────────────────────────────
    //  TRANG KẾT QUẢ TÌM KIẾM  →  GET /home/search?keyword=
    // ──────────────────────────────────────────────────────────
    public function search(): void
    {
        $keyword        = trim($_GET['keyword'] ?? '');
        $results        = $keyword !== '' ? $this->bookModel->searchFull($keyword) : [];
        $suggestedBooks = $this->bookModel->getRandomBooks(10);
        $categories     = $this->buildCategories();

        // search.php tự render full HTML (có header/footer riêng)
        // nên KHÔNG dùng layout.php
        require BASE_PATH . '/view/home/search.php';
    }

    // ──────────────────────────────────────────────────────────
    //  AJAX LIVE-SEARCH  →  GET /home/livesearch?key=
    //  ⚠ Tên method phải là livesearch (lowercase) vì router
    //    gọi strtolower($segments[1])
    // ──────────────────────────────────────────────────────────
    public function livesearch(): void
    {
        $key = trim($_GET['key'] ?? '');

        if ($key === '') {
            echo '';
            return;
        }

        $results = $this->bookModel->searchLive($key);
        // live_search.php chỉ trả về fragment HTML, không có layout
        require BASE_PATH . '/view/home/live_search.php';
    }

    // ──────────────────────────────────────────────────────────
    //  GIỚI THIỆU
    // ──────────────────────────────────────────────────────────
    public function gioithieu(): void
    {
        $categories = $this->buildCategories();
        $viewFile   = BASE_PATH . '/view/home/gioithieu.php';

        if (!file_exists($viewFile)) {
            require BASE_PATH . '/view/home/layout.php';
            return;
        }
        require BASE_PATH . '/view/home/layout.php';
    }

    // ──────────────────────────────────────────────────────────
    //  ALIAS AUTH — header.php dùng /home/login, v.v.
    //  Chỉ chuyển tiếp sang Auth controller
    // ──────────────────────────────────────────────────────────
    public function login(): void
    {
        require_once BASE_PATH . '/controller/Auth.php';
        (new Auth())->login();
    }

    public function register(): void
    {
        require_once BASE_PATH . '/controller/Auth.php';
        (new Auth())->register();
    }

    public function logout(): void
    {
        require_once BASE_PATH . '/controller/Auth.php';
        (new Auth())->logout();
    }

    public function profile(): void
    {
        require_once BASE_PATH . '/controller/Auth.php';
        (new Auth())->profile();
    }

    // ──────────────────────────────────────────────────────────
    //  Helper: build $categories cho mega-menu header
    //  Trả về: [ ['IDDanhMuc'=>..., 'TenDanhMuc'=>...,
    //              'theloai'=> [...] ], ... ]
    // ──────────────────────────────────────────────────────────
    private function buildCategories(): array
    {
        $list = $this->bookModel->getAllCategories();
        foreach ($list as &$cat) {
            $cat['theloai'] = $this->bookModel->getSubCategoriesByDanhMuc(
                (int)$cat['IDDanhMuc']
            );
        }
        unset($cat);
        return $list;
    }
}
