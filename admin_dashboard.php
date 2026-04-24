<?php
require_once 'config.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Get counts for dashboard
$count_danhmuc = $pdo->query("SELECT COUNT(*) FROM danhmuc")->fetchColumn();
$count_chitiet = $pdo->query("SELECT COUNT(*) FROM danhmucchitiet")->fetchColumn();
$count_mathang = $pdo->query("SELECT COUNT(*) FROM mathang")->fetchColumn();

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - SachKaka</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar { min-height: 100vh; background: #0f172a; color: white; }
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 10px 15px; display: block; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: #dc2626; color: white; }
        .navbar-admin { background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3" style="width: 250px;">
            <h4 class="text-center text-danger fw-bold mb-4">SachKaka Admin</h4>
            <a href="admin_dashboard.php" class="active"><i class="fas fa-home me-2"></i> Tổng Quan</a>
            <a href="admin_danhmuc.php"><i class="fas fa-folder me-2"></i> Danh Mục Cha</a>
            <a href="admin_danhmucchitiet.php"><i class="fas fa-folder-open me-2"></i> Danh Mục Chi Tiết</a>
            <a href="admin_mathang.php"><i class="fas fa-box me-2"></i> Mặt Hàng</a>
            <hr class="border-secondary">
            <a href="index.php"><i class="fas fa-arrow-left me-2"></i> Về Trang Web</a>
            <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng Xuất</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-admin p-3 mb-4">
                <div class="container-fluid justify-content-end">
                    <span class="fw-bold">Xin chào, Admin <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                </div>
            </nav>

            <div class="container-fluid px-4">
                <h2 class="fw-bold mb-4">Tổng Quan Hệ Thống</h2>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body">
                                <h6 class="text-muted">Tổng Danh Mục Cha</h6>
                                <h2 class="fw-bold"><?= $count_danhmuc ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body">
                                <h6 class="text-muted">Tổng Danh Mục Chi Tiết</h6>
                                <h2 class="fw-bold"><?= $count_chitiet ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body">
                                <h6 class="text-muted">Tổng Mặt Hàng</h6>
                                <h2 class="fw-bold"><?= $count_mathang ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
