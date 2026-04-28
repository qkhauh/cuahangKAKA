<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>const BASE_URL = '<?= url() ?>';</script>
    <title><?= $pageTitle ?? 'Admin - SachKaka' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar { min-height: 100vh; background: #0f172a; color: white; width: 250px; flex-shrink: 0; }
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 10px 15px; display: block; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: #dc2626; color: white; }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3">
        <h4 class="text-center text-danger fw-bold mb-4">SachKaka Admin</h4>
        <a href="<?= url('admin') ?>" class="<?= ($activeAdmin??'')==='dashboard' ? 'active':'' ?>">
            <i class="fas fa-home me-2"></i> Tổng Quan
        </a>
        <a href="<?= url('admin/danhmuc') ?>" class="<?= ($activeAdmin??'')==='danhmuc' ? 'active':'' ?>">
            <i class="fas fa-folder me-2"></i> Danh Mục
        </a>
        <a href="<?= url('admin/danhmucchitiet') ?>" class="<?= ($activeAdmin??'')==='danhmucchitiet' ? 'active':'' ?>">
            <i class="fas fa-folder-open me-2"></i> Danh Mục Sản Phẩm
        </a>
        <a href="<?= url('admin/mathang') ?>" class="<?= ($activeAdmin??'')==='mathang' ? 'active':'' ?>">
            <i class="fas fa-box me-2"></i> Mặt Hàng
        </a>
        <a href="<?= url('admin/donhang') ?>" class="<?= ($activeAdmin??'')==='donhang' ? 'active':'' ?>">
            <i class="fas fa-shopping-cart me-2"></i> Đơn Hàng
        </a>
        <hr class="border-secondary">
        <a href="<?= url('/') ?>"><i class="fas fa-arrow-left me-2"></i> Về Trang Web</a>
        <a href="<?= url('logout') ?>" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng Xuất</a>
    </div>

    <div class="flex-grow-1" style="overflow-y:auto;height:100vh">
        <?= $content ?>
    </div>
</div>

<div class="modal fade" id="globalConfirmModal" tabindex="-1" style="z-index: 10000;">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <h5 class="mb-3" id="globalConfirmMessage">Bạn có chắc chắn không?</h5>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger px-4" id="globalConfirmBtn">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let confirmCallback = null;
const confirmModal = new bootstrap.Modal(document.getElementById('globalConfirmModal'));

document.getElementById('globalConfirmBtn').addEventListener('click', () => {
    confirmModal.hide();
    if (confirmCallback) confirmCallback();
});

function showConfirmModal(message, callback) {
    document.getElementById('globalConfirmMessage').innerText = message;
    confirmCallback = callback;
    confirmModal.show();
}

function confirmFormSubmit(form, message) {
    if (form.dataset.confirmed === 'true') return true;
    showConfirmModal(message, () => {
        form.dataset.confirmed = 'true';
        const btn = form.querySelector('[type="submit"]');
        if (btn) { btn.click(); } else { form.requestSubmit(); }
    });
    return false;
}
</script>
</body>
</html>
