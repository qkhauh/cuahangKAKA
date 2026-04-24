<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $matkhau = $_POST['matkhau'];

    if (empty($email) || empty($matkhau)) {
        $error = "Vui lòng nhập đầy đủ email và mật khẩu.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM nguoidung WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($matkhau, $user['matkhau'])) {
            if ($user['trangthai'] == 1) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['hoten'];
                $_SESSION['user_role'] = $user['phanquyen'];
                
                header("Location: index.php");
                exit;
            } else {
                $error = "Tài khoản của bạn đã bị khóa.";
            }
        } else {
            $error = "Email hoặc mật khẩu không chính xác.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - SachKaka</title>
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
    <div class="auth-wrapper">
        <div class="glass-panel auth-card">
            <h2 class="text-center mb-4 fw-bold">Đăng Nhập</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="background: rgba(220, 38, 38, 0.1); border-color: #dc2626; color: #dc2626;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
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
                    Chưa có tài khoản? <a href="register.php" class="text-decoration-none" style="color: var(--primary-color);">Đăng ký ngay</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
