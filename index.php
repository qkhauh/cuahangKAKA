<?php
require_once 'config.php';

// Fetch Categories for Navbar
$stmt_cats = $pdo->query("SELECT * FROM danhmuc");
$categories = $stmt_cats->fetchAll();

$categories_with_details = [];
foreach ($categories as $cat) {
    $stmt_details = $pdo->prepare("SELECT * FROM danhmucchitiet WHERE danhmuc_id = ?");
    $stmt_details->execute([$cat['id']]);
    $cat['details'] = $stmt_details->fetchAll();
    $categories_with_details[] = $cat;
}

// Fetch Featured Products (Random or Latest)
$stmt_products = $pdo->query("SELECT m.*, d.tendanhmuc as tendanhmuc_chitiet FROM mathang m JOIN danhmucchitiet d ON m.danhmucchitiet_id = d.id ORDER BY m.id DESC LIMIT 8");
$featured_products = $stmt_products->fetchAll();

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - SachKaka</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">SachKaka</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Trang Chủ</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" aria-expanded="false">
                            Danh Mục
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDropdown">
                            <?php foreach ($categories_with_details as $cat): ?>
                                <li class="dropdown-submenu position-relative">
                                    <h6 class="dropdown-header text-danger fw-bold"><?= htmlspecialchars($cat['tendanhmuc']) ?></h6>
                                    <?php foreach ($cat['details'] as $detail): ?>
                                        <a class="dropdown-item" href="products.php?category=<?= $detail['id'] ?>">
                                            <?= htmlspecialchars($detail['tendanhmuc']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                    <div class="dropdown-divider"></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Liên Hệ</a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <form class="search-form mx-lg-3 my-2 my-lg-0" action="products.php" method="GET">
                    <input class="form-control form-control-sm" type="search" name="q" placeholder="Tìm kiếm sách, đồ chơi..." aria-label="Search">
                    <button class="btn-search" type="submit"><i class="fas fa-search"></i></button>
                </form>

                <!-- Cart Icon -->
                <a href="#" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge">0</span>
                </a>

                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div style="width: 35px; height: 35px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-weight: bold; color: var(--primary-color);">
                                    <?= strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                                </div>
                                <span>Xin chào, <strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end glass-panel" aria-labelledby="userDropdown">
                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <li><a class="dropdown-item text-light" href="admin_dashboard.php"><i class="fas fa-cogs me-2"></i>Quản trị hệ thống</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item text-light" href="#"><i class="fas fa-user me-2"></i>Thông tin tài khoản</a></li>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li><a class="dropdown-item text-danger-custom" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Đăng Xuất</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-light btn-sm me-2" style="border-radius: 0.5rem; border-color: rgba(255,255,255,0.5);">Đăng Nhập</a>
                        <a href="register.php" class="btn btn-light btn-sm" style="color: var(--primary-color); font-weight: 600; border-radius: 0.5rem;">Đăng Ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Khám Phá Tri Thức<br><span style="color: var(--primary-color);">Vô Tận</span></h1>
            <p class="hero-subtitle">Chào mừng bạn đến với SachKaka, nơi cung cấp những cuốn sách hay nhất, dụng cụ học tập và đồ chơi chất lượng cao với giá cả hợp lý.</p>
            <a href="products.php" class="btn btn-primary-custom btn-lg px-5 py-3 fs-5 shadow">Mua Sắm Ngay</a>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="container mb-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Sản Phẩm Mới Nhất</h3>
            <a href="products.php" class="text-decoration-none fw-bold" style="color: var(--primary-color);">Xem tất cả →</a>
        </div>
        
        <div class="row g-4">
            <?php foreach($featured_products as $product): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card glass-panel book-card h-100 border-0">
                    <div class="book-img">
                        <!-- Placeholder if no image -->
                        <?php if(!empty($product['hinhanh'])): ?>
                            <!-- Use placeholder since actual images don't exist yet -->
                            <div style="width:100%; height:100%; background-color:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        <?php else: ?>
                            <div style="width:100%; height:100%; background-color:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge bg-danger w-auto mb-2" style="align-self: flex-start; font-size: 0.7rem;"><?= htmlspecialchars($product['tendanhmuc_chitiet']) ?></span>
                        <h6 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($product['tenmathang']) ?>"><?= htmlspecialchars($product['tenmathang']) ?></h6>
                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2">
                            <div>
                                <?php if($product['giagoc'] > $product['giaban']): ?>
                                    <small class="text-decoration-line-through text-muted d-block" style="font-size:0.7rem;"><?= number_format($product['giagoc'], 0, ',', '.') ?> ₫</small>
                                <?php endif; ?>
                                <span class="fw-bold fs-6 text-danger-custom"><?= number_format($product['giaban'], 0, ',', '.') ?> ₫</span>
                            </div>
                            <button class="btn btn-sm btn-outline-danger rounded-circle" style="width: 30px; height: 30px; padding:0; display:flex; align-items:center; justify-content:center;"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto py-5" style="background: #ffffff; border-top: 1px solid var(--glass-border);">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3" style="color: var(--primary-color);">SachKaka</h5>
                    <p class="text-muted">Hệ thống nhà sách uy tín, mang tri thức đến mọi nhà.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Liên kết</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-muted text-decoration-none">Trang chủ</a></li>
                        <li><a href="products.php" class="text-muted text-decoration-none">Sản phẩm</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Về chúng tôi</a></li>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
