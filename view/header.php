
<?php
// Đảm bảo $categories luôn có giá trị để tránh PHP Notice/Warning

if (!isset($categories)) {
    $categories = [];
}
?>

<header class="header">
    <div class="container">
        <div class="nav">

            <!-- ===== NAV LINKS ===== -->
            <div class="nav-link">
                <ul class="list-link">
                    <li><a class="link" href="index.php">Trang chủ</a></li>
                    <li><a class="link" href="index.php?controller=Home&action=gioithieu">Giới thiệu</a></li>

                    <!-- Mega Menu Thể Loại -->
                    <li class="dropdown">
                        <a class="link" href="#">Thể loại</a>

                        <div class="mega-menu">
                            <?php foreach ($categories as $dm): ?>
                            <div class="column">

                                <!-- Danh muc -->
                                <a class="link"
                                   href="index.php?controller=Book&action=danhmuc&param=<?= (int)$dm['IDDanhMuc'] ?>">
                                    <?= htmlspecialchars($dm['TenDanhMuc']) ?>
                                </a>

                                <!-- The loai con -->
                                <ul>
                                    <?php foreach ($dm['theloai'] as $tl): ?>
                                    <li>
                                        <a class="link"
                                           href="index.php?controller=Book&action=theloai&param=<?= (int)$tl['IDTheLoai'] ?>">
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
                <a href="index.php">
                    <img width="200" height="60"
                         src="public/images/bookstore_logo.png"
                         alt="BookStore Logo">
                </a>
            </div>

            <!-- ===== SEARCH + CART + LOGIN ===== -->
            <div class="search-login">

                <!-- Tim kiem -->
                <div class="menu-search">
                    <form action="index.php" method="GET">
                        <input type="hidden" name="controller" value="Home">
                        <input type="hidden" name="action" value="search">
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

                <!-- Gio hang -->
                <div class="menu-cart">
                    <a href="index.php?controller=Book&action=cart" class="button-cart link">
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

                <!-- Dang nhap / tai khoan -->
                <div class="menu-login">
                    <button class="button-login">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu-login">
                        <?php if (!isset($_SESSION['IDNguoiDung'])): ?>
                            <li><a class="link" href="index.php?controller=User&action=login">Đăng nhập</a></li>
                            <li><a class="link" href="index.php?controller=User&action=register">Đăng ký</a></li>
                        <?php else: ?>
                            <li>
                                <a class="link" href="index.php?controller=User&action=profile">
                                    <i class="fa-solid fa-user"></i>
                                    <?= htmlspecialchars($_SESSION['HoTen']) ?>
                                </a>
                            </li>
                            <li>
                                <a class="link" href="index.php?controller=User&action=logout">
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

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const keyword = this.value.trim();

        if (keyword.length === 0) {
            searchResult.innerHTML = '';
            searchResult.style.display = 'none';
            return;
        }

        fetch('index.php?controller=Home&action=livesearch&key=' + encodeURIComponent(keyword))
            .then(res => res.text())
            .then(data => {
                searchResult.innerHTML = data;
                searchResult.style.display = data.trim() ? 'block' : 'none';
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
}
</script>
