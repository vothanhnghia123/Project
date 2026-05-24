<?php
/**
 * view/auth/register.php
 * Form đăng ký tài khoản
 * Biến nhận từ controller: $error (string), $success (string)
 */
?>

<div class="auth-container">

    <h2>Đăng ký</h2>

    <?php if ($error !== ''): ?>
        <p class="login-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <p style="color:green; margin-bottom:10px;">
            <?= htmlspecialchars($success) ?>
            <a href="<?= BASE_URL ?>/auth/login">Đăng nhập ngay</a>
        </p>
    <?php endif; ?>

    <form method="POST" class="auth-form">

        <input
            type="text"
            name="hoten"
            placeholder="Họ và tên"
            value="<?= htmlspecialchars($_POST['hoten'] ?? '') ?>"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="Email"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            required
        >

        <input
            type="password"
            name="matkhau"
            placeholder="Mật khẩu (tối thiểu 6 ký tự)"
            required
        >

        <input
            type="password"
            name="repass"
            placeholder="Nhập lại mật khẩu"
            required
        >

        <button type="submit">Đăng ký</button>

    </form>

    <p class="auth-switch">
        Đã có tài khoản?
        <a href="<?= BASE_URL ?>/auth/login">Đăng nhập</a>
    </p>

</div>
