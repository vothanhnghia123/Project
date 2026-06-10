<?php
require_once "model/ModelBook.php";
require_once "model/ModelNews.php";

class Home {
    private $bookModel;
    private $newsModel;

    public function __construct() {
        $this->bookModel = new ModelBook();
        $this->newsModel = new ModelNews();
    }

    // ── Trang chủ ──────────────────────────────────────────────
    public function index() {
        $newBooks       = $this->bookModel->getNewBooks(8);
        $vanhocBooks    = $this->bookModel->getBooksByCategory('Văn học', 10);
        $vanhocDMId     = $this->bookModel->getCategoryIdByName('Văn học');
        $thieunhiBooks  = $this->bookModel->getBooksByCategory('Thiếu nhi', 10);
        $thieunhiDMId   = $this->bookModel->getCategoryIdByName('Thiếu nhi');
        $suggestedBooks = $this->bookModel->getRandomBooks(10);
        $categories     = $this->buildCategories();

        require_once "view/home/index.php";
    }

    // ── Tìm kiếm ───────────────────────────────────────────────
    public function search() {
        $keyword        = trim($_GET['keyword'] ?? '');
        $results        = $keyword !== '' ? $this->bookModel->searchFull($keyword) : [];
        $suggestedBooks = $this->bookModel->getRandomBooks(10);
        $categories     = $this->buildCategories();

        require_once "view/home/search.php";
    }

    public function livesearch() {
        $key = trim($_GET['key'] ?? '');
        if ($key === '') { echo ''; return; }

        $results = $this->bookModel->searchLive($key);
        require_once "view/home/live_search.php";
    }

    // ── Giới thiệu ─────────────────────────────────────────────
    public function gioithieu() {
        $categories = $this->buildCategories();
        require_once "view/home/gioithieu.php";
    }

    // ── Auth alias ─────────────────────────────────────────────
    public function login() {
        require_once "controller/User.php";
        $u = new User();
        $u->login();
    }

    public function register() {
        require_once "controller/User.php";
        $u = new User();
        $u->register();
    }

    public function logout() {
        require_once "controller/User.php";
        $u = new User();
        $u->logout();
    }

    public function profile() {
        require_once "controller/User.php";
        $u = new User();
        $u->profile();
    }

    // ── Helper ─────────────────────────────────────────────────
    private function buildCategories() {
        $list = $this->newsModel->getAllDanhmuc();
        foreach ($list as &$cat) {
            $cat['theloai'] = $this->newsModel->getTheloaiByDanhmuc((int)$cat['IDDanhMuc']);
        }
        unset($cat);
        return $list;
    }
}
