<?php if (!empty($results)): ?>
    <?php foreach ($results as $book): ?>
    <a href="index.php?controller=Book&action=detail&param=<?= (int)$book['IDSach'] ?>"
       class="search-item link">
        <img src="public/images/sach/<?= htmlspecialchars($book['HinhAnh']) ?>"
             width="40"
             alt="<?= htmlspecialchars($book['TenSach']) ?>">
        <span><?= htmlspecialchars($book['TenSach']) ?></span>
    </a>
    <?php endforeach; ?>
<?php else: ?>
    <div class="search-item" style="color:#999; cursor:default;">Không tìm thấy kết quả</div>
<?php endif; ?>
