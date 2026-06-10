<div class="auth-container">

    <h2>Đăng nhập</h2>

    <?php if ($error !== ''): ?>
        <p class="login-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="auth-form">

        <input
            type="email"
            name="email"
            placeholder="Email"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            required>

        <input
            type="password"
            name="matkhau"
            placeholder="Mật khẩu"
            required>

        <button type="submit">Đăng nhập</button>

    </form>

    <p class="auth-switch">
        Bạn chưa có tài khoản?
        <a href="index.php?controller=User&action=register">Đăng ký</a>
    </p>

</div>
