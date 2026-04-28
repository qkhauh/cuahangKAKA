<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'SachKaka' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="<?= url('view/css/style.css') ?>" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-glass sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= url('/') ?>">SachKaka</a>
        <script>const BASE_URL = '<?= url() ?>';</script>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($activeNav??'')==='home' ? 'active':'' ?>" href="<?= url('/') ?>">Trang Chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= ($activeNav??'')==='products' ? 'active':'' ?>"
                       href="#" id="navbarDropdown" role="button" aria-expanded="false">Danh Mục</a>
                    <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDropdown">
                        <?php foreach ($categories ?? [] as $cat): ?>
                        <li class="dropdown-submenu position-relative">
                            <h6 class="dropdown-header text-danger fw-bold"><?= htmlspecialchars($cat['tendanhmuc']) ?></h6>
                            <?php foreach ($cat['details'] as $detail): ?>
                            <a class="dropdown-item" href="<?= url('products?category=' . $detail['id']) ?>">
                                <?= htmlspecialchars($detail['tendanhmuc']) ?>
                            </a>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="#footer">Liên Hệ</a></li>
            </ul>

            <form class="search-form mx-lg-3 my-2 my-lg-0" action="<?= url('products') ?>" method="GET">
                <input class="form-control form-control-sm" type="search" name="q"
                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Tìm kiếm...">
                <button class="btn-search" type="submit"><i class="fas fa-search"></i></button>
            </form>

            <a href="<?= url('cart') ?>" class="cart-icon"><i class="fas fa-shopping-cart"></i><span class="cart-badge" id="navCartBadge">0</span></a>

            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                       id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div style="width:35px;height:35px;border-radius:50%;background:white;display:flex;align-items:center;justify-content:center;margin-right:10px;font-weight:bold;color:var(--primary-color);">
                            <?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>
                        </div>
                        <span>Xin chào, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end glass-panel" aria-labelledby="userDropdown">
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li><a class="dropdown-item text-light" href="<?= url('admin') ?>"><i class="fas fa-cogs me-2"></i>Quản trị hệ thống</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item text-light" href="<?= url('my-orders') ?>"><i class="fas fa-box me-2"></i>Đơn hàng của tôi</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li><a class="dropdown-item text-danger-custom" href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Đăng Xuất</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a href="<?= url('login') ?>" class="btn btn-outline-light btn-sm me-2" style="border-radius:.5rem;border-color:rgba(255,255,255,.5)">Đăng Nhập</a>
                <a href="<?= url('register') ?>" class="btn btn-light btn-sm" style="color:var(--primary-color);font-weight:600;border-radius:.5rem">Đăng Ký</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<?= $content ?>

<footer id="footer" class="mt-auto py-5" style="background:#fff;border-top:1px solid var(--glass-border)">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <h5 class="fw-bold mb-3" style="color:var(--primary-color)">SachKaka</h5>
                <p class="text-muted">Hệ thống nhà sách uy tín, mang tri thức đến mọi nhà.</p>
            </div>
            <div class="col-md-4">
                <h5 class="fw-bold mb-3">Liên kết</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= url('/') ?>" class="text-muted text-decoration-none">Trang chủ</a></li>
                    <li><a href="<?= url('products') ?>" class="text-muted text-decoration-none">Sản phẩm</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="fw-bold mb-3">Liên hệ</h5>
                <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>contact@sachkaka.com</p>
                <p class="text-muted"><i class="fas fa-phone me-2"></i>1900 1234</p>
            </div>
        </div>
        <hr class="border-secondary opacity-25">
        <p class="mb-0 text-center text-muted">&copy; 2026 SachKaka. Bảo lưu mọi quyền.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const IS_LOGGED_IN = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

function getCart() {
    let cart = localStorage.getItem('sachkaka_cart');
    return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) {
    localStorage.setItem('sachkaka_cart', JSON.stringify(cart));
    updateCartBadge();
}

function updateCartBadge() {
    const cart = getCart();
    const count = cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById('navCartBadge');
    if (badge) badge.innerText = count;
}

function addToCart(id, name, price, img, qty = 1, maxQty = 999) {
    const cart = getCart();
    const idx = cart.findIndex(item => item.id === id);
    if (idx >= 0) {
        cart[idx].qty += qty;
        if (cart[idx].qty > maxQty) {
            cart[idx].qty = maxQty;
            alert('Sản phẩm ' + name + ' chỉ còn ' + maxQty + ' sản phẩm trong kho.');
        }
        cart[idx].maxQty = maxQty;
    } else {
        if (qty > maxQty) {
            qty = maxQty;
            alert('Sản phẩm ' + name + ' chỉ còn ' + maxQty + ' sản phẩm trong kho.');
        }
        cart.push({ id, name, price, img, qty, maxQty, selected: true });
    }
    saveCart(cart);
}

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.add-to-cart-btn');
    if (btn) {
        e.preventDefault();
        if (!IS_LOGGED_IN) {
            window.location.href = '<?= url('login') ?>';
            return;
        }
        const id = parseInt(btn.dataset.id);
        const name = btn.dataset.name;
        const price = parseFloat(btn.dataset.price);
        const img = btn.dataset.img;
        const maxQty = parseInt(btn.dataset.stock) || 999;
        addToCart(id, name, price, img, 1, maxQty);
        
        // Simple visual feedback
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.replace('btn-outline-danger', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.replace('btn-success', 'btn-outline-danger');
        }, 1000);
    }
});

document.addEventListener('DOMContentLoaded', updateCartBadge);
</script>
</body>
</html>
