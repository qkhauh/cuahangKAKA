<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$message = '';

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = trim($_POST['tenmathang']);
        $danhmucchitiet_id = $_POST['danhmucchitiet_id'];
        $giagoc = $_POST['giagoc'] ?: 0;
        $giaban = $_POST['giaban'] ?: 0;
        $soluongton = $_POST['soluongton'] ?: 0;
        
        $stmt = $pdo->prepare("INSERT INTO mathang (tenmathang, giagoc, giaban, soluongton, danhmucchitiet_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $giagoc, $giaban, $soluongton, $danhmucchitiet_id]);
        $message = "Thêm mặt hàng thành công!";
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM mathang WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Xóa thành công!";
    }
}

// Fetch all
$stmt = $pdo->query("SELECT m.*, d.tendanhmuc as tendanhmuc_chitiet FROM mathang m JOIN danhmucchitiet d ON m.danhmucchitiet_id = d.id ORDER BY m.id DESC");
$items = $stmt->fetchAll();

// Fetch detail categories for dropdown
$detail_cats = $pdo->query("SELECT d.*, c.tendanhmuc as tendanhmuc_cha FROM danhmucchitiet d JOIN danhmuc c ON d.danhmuc_id = c.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Mặt Hàng - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar { min-height: 100vh; background: #0f172a; color: white; }
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 10px 15px; display: block; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: #dc2626; color: white; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3" style="width: 250px;">
            <h4 class="text-center text-danger fw-bold mb-4">SachKaka Admin</h4>
            <a href="admin_dashboard.php"><i class="fas fa-home me-2"></i> Tổng Quan</a>
            <a href="admin_danhmuc.php"><i class="fas fa-folder me-2"></i> Danh Mục Cha</a>
            <a href="admin_danhmucchitiet.php"><i class="fas fa-folder-open me-2"></i> Danh Mục Chi Tiết</a>
            <a href="admin_mathang.php" class="active"><i class="fas fa-box me-2"></i> Mặt Hàng</a>
            <hr class="border-secondary">
            <a href="index.php"><i class="fas fa-arrow-left me-2"></i> Về Trang Web</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4" style="height: 100vh; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Quản Lý Mặt Hàng</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i>Thêm Mới
                </button>
            </div>

            <?php if($message): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Mặt Hàng</th>
                                    <th>Danh Mục</th>
                                    <th>Giá Gốc</th>
                                    <th>Giá Bán</th>
                                    <th>Tồn Kho</th>
                                    <th class="text-end">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($items as $item): ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td class="text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($item['tenmathang']) ?>">
                                        <?= htmlspecialchars($item['tenmathang']) ?>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($item['tendanhmuc_chitiet']) ?></span></td>
                                    <td><?= number_format($item['giagoc'], 0, ',', '.') ?> ₫</td>
                                    <td class="text-danger fw-bold"><?= number_format($item['giaban'], 0, ',', '.') ?> ₫</td>
                                    <td><?= $item['soluongton'] ?></td>
                                    <td class="text-end">
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                            <button type="submit" name="delete" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Mặt Hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Tên Mặt Hàng *</label>
                            <input type="text" name="tenmathang" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Danh Mục *</label>
                            <select name="danhmucchitiet_id" class="form-select" required>
                                <?php foreach($detail_cats as $cat): ?>
                                    <option value="<?= $cat['id'] ?>">
                                        <?= htmlspecialchars($cat['tendanhmuc_cha']) ?> > <?= htmlspecialchars($cat['tendanhmuc']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Giá Gốc</label>
                            <input type="number" name="giagoc" class="form-control" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Giá Bán *</label>
                            <input type="number" name="giaban" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Số Lượng Tồn Kho</label>
                            <input type="number" name="soluongton" class="form-control" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="add" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
