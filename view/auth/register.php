<div class="auth-wrapper" style="padding:4rem 0">
    <div class="glass-panel auth-card" style="max-width:600px">
        <h2 class="text-center mb-4 fw-bold">Đăng Ký Tài Khoản</h2>
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="background:rgba(220,38,38,.1);border-color:#dc2626;color:#dc2626"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
        <div class="alert alert-success" style="background:rgba(5,150,105,.1);border-color:#059669;color:#059669"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= url('register') ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="hoten" class="form-label">Họ và Tên *</label>
                    <input type="text" class="form-control form-control-glass" id="hoten" name="hoten" required
                           placeholder="Nguyễn Văn A" value="<?= htmlspecialchars($old['hoten'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control form-control-glass" id="email" name="email" required
                           placeholder="email@example.com" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="matkhau" class="form-label">Mật Khẩu *</label>
                    <input type="password" class="form-control form-control-glass" id="matkhau" name="matkhau" required placeholder="Tạo mật khẩu">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="matkhau2" class="form-label">Nhập Lại Mật Khẩu *</label>
                    <input type="password" class="form-control form-control-glass" id="matkhau2" name="matkhau2" required placeholder="Xác nhận mật khẩu">
                </div>
            </div>
            <div class="mb-3">
                <label for="sodienthoai" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control form-control-glass" id="sodienthoai" name="sodienthoai"
                       placeholder="0901234567" value="<?= htmlspecialchars($old['sodienthoai'] ?? '') ?>">
            </div>
            <div class="mb-4">
                <label for="diachi" class="form-label">Địa Chỉ</label>
                <textarea class="form-control form-control-glass" id="diachi" name="diachi" rows="2"
                          placeholder="Nhập địa chỉ nhận hàng"><?= htmlspecialchars($old['diachi'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary-custom w-100 mb-3">Đăng Ký</button>
            <div class="text-center text-muted">
                Đã có tài khoản? <a href="<?= url('login') ?>" class="text-decoration-none" style="color:var(--primary-color)">Đăng nhập ngay</a>
            </div>
        </form>
    </div>
</div>
