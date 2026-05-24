<?php
/**
 * view/home/live_search.php
 * Trả về đoạn HTML nhỏ cho dropdown live-search.
 * Biến: $results (mảng từ BookModel::searchLive)
 */
foreach ($results as $book): ?>
<a href="<?= BASE_URL ?>/book/detail/<?= $book['IDSach'] ?>"
   class="search-item link">
    <img src="<?= BASE_URL ?>/public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
         width="40"
         alt="<?= htmlspecialchars($book['TenSach']) ?>">
    <span><?= htmlspecialchars($book['TenSach']) ?></span>
</a>
<?php endforeach;
