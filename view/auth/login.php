<div class="auth-wrapper">
    <div class="glass-panel auth-card">
        <h2 class="text-center mb-4 fw-bold">Đăng Nhập</h2>
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="background:rgba(220,38,38,.1);border-color:#dc2626;color:#dc2626">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        <form method="POST" action="<?= url('login') ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control form-control-glass" id="email" name="email" required placeholder="Nhập email của bạn">
            </div>
            <div class="mb-4">
                <label for="matkhau" class="form-label">Mật Khẩu</label>
                <input type="password" class="form-control form-control-glass" id="matkhau" name="matkhau" required placeholder="Nhập mật khẩu">
            </div>
            <button type="submit" class="btn btn-primary-custom w-100 mb-3">Đăng Nhập</button>
            <div class="text-center text-muted">
                Chưa có tài khoản? <a href="<?= url('register') ?>" class="text-decoration-none" style="color:var(--primary-color)">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div>
