<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $matkhau = $_POST['matkhau'];
    $matkhau2 = $_POST['matkhau2'];
    $hoten = trim($_POST['hoten']);
    $sodienthoai = trim($_POST['sodienthoai']);
    $diachi = trim($_POST['diachi']);

    if (empty($email) || empty($matkhau) || empty($hoten)) {
        $error = "Vui lòng nhập đầy đủ Email, Mật khẩu và Họ tên.";
    } elseif ($matkhau !== $matkhau2) {
        $error = "Mật khẩu nhập lại không khớp.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM nguoidung WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email này đã được sử dụng.";
        } else {
            // Hash password
            $hashed_password = password_hash($matkhau, PASSWORD_DEFAULT);
            
            // Insert user
            $stmt = $pdo->prepare("INSERT INTO nguoidung (email, matkhau, hoten, sodienthoai, diachi, phanquyen) VALUES (?, ?, ?, ?, ?, 'khachhang')");
            if ($stmt->execute([$email, $hashed_password, $hoten, $sodienthoai, $diachi])) {
                $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
            } else {
                $error = "Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - SachKaka</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">SachKaka</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="auth-wrapper" style="padding: 4rem 0;">
        <div class="glass-panel auth-card" style="max-width: 600px;">
            <h2 class="text-center mb-4 fw-bold">Đăng Ký Tài Khoản</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="background: rgba(220, 38, 38, 0.1); border-color: #dc2626; color: #dc2626;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success" style="background: rgba(5, 150, 105, 0.1); border-color: #059669; color: #059669;">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="hoten" class="form-label">Họ và Tên *</label>
                        <input type="text" class="form-control form-control-glass" id="hoten" name="hoten" required placeholder="Nguyễn Văn A" value="<?php echo isset($_POST['hoten']) ? htmlspecialchars($_POST['hoten']) : ''; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control form-control-glass" id="email" name="email" required placeholder="email@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
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
                    <input type="text" class="form-control form-control-glass" id="sodienthoai" name="sodienthoai" placeholder="0901234567" value="<?php echo isset($_POST['sodienthoai']) ? htmlspecialchars($_POST['sodienthoai']) : ''; ?>">
                </div>

                <div class="mb-4">
                    <label for="diachi" class="form-label">Địa Chỉ</label>
                    <textarea class="form-control form-control-glass" id="diachi" name="diachi" rows="2" placeholder="Nhập địa chỉ nhận hàng"><?php echo isset($_POST['diachi']) ? htmlspecialchars($_POST['diachi']) : ''; ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 mb-3">Đăng Ký</button>
                <div class="text-center text-muted">
                    Đã có tài khoản? <a href="login.php" class="text-decoration-none" style="color: var(--primary-color);">Đăng nhập ngay</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
