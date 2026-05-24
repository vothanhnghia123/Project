<header class="header">
    <div class="container">
        <div class="nav">

            <!-- ===== NAV LINKS ===== -->
            <div class="nav-link">
                <ul class="list-link">
                    <li><a class="link" href="<?= BASE_URL ?>/">Trang chủ</a></li>
                    <li><a class="link" href="<?= BASE_URL ?>/home/gioithieu">Giới thiệu</a></li>

                    <!-- Mega Menu Thể Loại -->
                    <li class="dropdown">
                        <a class="link" href="#">Thể loại</a>

                        <div class="mega-menu">
                            <?php foreach ($categories as $dm): ?>
                            <div class="column">

                                <!-- Danh mục -->
                                <a class="link"
                                   href="<?= BASE_URL ?>/book/danhmuc/<?= $dm['IDDanhMuc'] ?>">
                                    <?= htmlspecialchars($dm['TenDanhMuc']) ?>
                                </a>

                                <!-- Các thể loại con -->
                                <ul>
                                    <?php foreach ($dm['theloai'] as $tl): ?>
                                    <li>
                                        <a class="link"
                                           href="<?= BASE_URL ?>/book/theloai/<?= $tl['IDTheLoai'] ?>">
                                            <?= htmlspecialchars($tl['TenTheLoai']) ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>

                            </div>
                            <?php endforeach; ?>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- ===== LOGO ===== -->
            <div class="nav-logo">
                <a href="<?= BASE_URL ?>/">
                    <img width="200" height="60"
                         src="<?= BASE_URL ?>/public/images/bookstore_logo.png"
                         alt="BookStore Logo">
                </a>
            </div>

            <!-- ===== SEARCH + CART + LOGIN ===== -->
            <div class="search-login">

                <!-- Tìm kiếm -->
                <div class="menu-search">
                    <form action="<?= BASE_URL ?>/home/search" method="GET">
                        <button type="submit" class="button-search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <input
                            type="search"
                            id="search-input"
                            name="keyword"
                            required
                            class="input-search"
                            placeholder="Nhập để tìm...">
                    </form>
                    <div id="search-result" class="search-result"></div>
                </div>

                <!-- Giỏ hàng -->
                <div class="menu-cart">
                    <a href="<?= BASE_URL ?>/book/cart" class="button-cart link">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php
                        $cartCount = 0;
                        if (isset($_SESSION['sl_them_vao_gio'])) {
                            foreach ($_SESSION['sl_them_vao_gio'] as $sl) {
                                $cartCount += $sl;
                            }
                        }
                        ?>
                        <span id="cart-count" class="cart-count"
                              style="<?= $cartCount == 0 ? 'display:none' : '' ?>">
                            <?= $cartCount ?>
                        </span>
                    </a>
                </div>

                <!-- Đăng nhập / tài khoản -->
                <div class="menu-login">
                    <button class="button-login">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu-login">
                        <?php if (!isset($_SESSION['IDNguoiDung'])): ?>
                            <li><a class="link" href="<?= BASE_URL ?>/home/login">Đăng nhập</a></li>
                            <li><a class="link" href="<?= BASE_URL ?>/home/register">Đăng ký</a></li>
                        <?php else: ?>
                            <li>
                                <a class="link" href="<?= BASE_URL ?>/home/profile">
                                    <i class="fa-solid fa-user"></i>
                                    <?= htmlspecialchars($_SESSION['HoTen']) ?>
                                </a>
                            </li>
                            <li>
                                <a class="link" href="<?= BASE_URL ?>/home/logout">
                                    <i class="fa-solid fa-right-to-bracket"></i> Đăng xuất
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div><!-- /search-login -->
        </div><!-- /nav -->
    </div><!-- /container -->
</header>

<script>
/* ---- Live Search ---- */
const searchInput  = document.getElementById('search-input');
const searchResult = document.getElementById('search-result');

searchInput.addEventListener('input', function () {
    const keyword = this.value.trim();

    if (keyword.length === 0) {
        searchResult.innerHTML = '';
        searchResult.style.display = 'none';
        return;
    }

    fetch('<?= BASE_URL ?>/home/liveSearch?key=' + encodeURIComponent(keyword))
        .then(res => res.text())
        .then(data => {
            searchResult.innerHTML = data;
            searchResult.style.display = 'block';
        });
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('.menu-search')) {
        searchResult.style.display = 'none';
    }
});

searchInput.addEventListener('focus', function () {
    if (this.value.trim().length > 0) {
        searchResult.style.display = 'block';
    }
});
</script>
