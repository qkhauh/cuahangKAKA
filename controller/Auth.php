<?php
class Auth extends Controller {
    public function showLogin(): void {
        $this->render('auth/login', ['pageTitle' => 'Đăng Nhập - SachKaka', 'error' => '']);
    }

    public function login(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['matkhau'] ?? '';
        $error = '';

        if (empty($email) || empty($password)) {
            $error = 'Vui lòng nhập đầy đủ email và mật khẩu.';
        } else {
            $model = new NguoiDungModel();
            $user = $model->findByEmail($email);
            if ($user && password_verify($password, $user['matkhau'])) {
                if ($user['trangthai'] == 1) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['hoten'];
                    $_SESSION['user_role'] = $user['phanquyen'];
                    $this->redirect(url('/'));
                } else {
                    $error = 'Tài khoản của bạn đã bị khóa.';
                }
            } else {
                $error = 'Email hoặc mật khẩu không chính xác.';
            }
        }
        $this->render('auth/login', ['pageTitle' => 'Đăng Nhập - SachKaka', 'error' => $error]);
    }

    public function showRegister(): void {
        $this->render('auth/register', ['pageTitle' => 'Đăng Ký - SachKaka', 'error' => '', 'success' => '', 'old' => []]);
    }

    public function register(): void {
        $email = trim($_POST['email'] ?? '');
        $matkhau = $_POST['matkhau'] ?? '';
        $matkhau2 = $_POST['matkhau2'] ?? '';
        $hoten = trim($_POST['hoten'] ?? '');
        $sodienthoai = trim($_POST['sodienthoai'] ?? '');
        $diachi = trim($_POST['diachi'] ?? '');
        $error = ''; $success = '';

        if (empty($email) || empty($matkhau) || empty($hoten)) {
            $error = 'Vui lòng nhập đầy đủ Email, Mật khẩu và Họ tên.';
        } elseif ($matkhau !== $matkhau2) {
            $error = 'Mật khẩu nhập lại không khớp.';
        } else {
            $model = new NguoiDungModel();
            if ($model->emailExists($email)) {
                $error = 'Email này đã được sử dụng.';
            } else {
                $model->create([
                    'email' => $email,
                    'matkhau' => password_hash($matkhau, PASSWORD_DEFAULT),
                    'hoten' => $hoten, 'sodienthoai' => $sodienthoai, 'diachi' => $diachi,
                ]);
                $success = 'Đăng ký thành công! Bạn có thể đăng nhập ngay.';
            }
        }
        $this->render('auth/register', [
            'pageTitle' => 'Đăng Ký - SachKaka',
            'error' => $error, 'success' => $success,
            'old' => compact('email', 'hoten', 'sodienthoai', 'diachi'),
        ]);
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        $this->redirect(url('/'));
    }
}
