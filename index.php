<?php
define('ROOT', __DIR__);
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'sachkaka');
define('DB_USER', 'root');
define('DB_PASS', '');

function url(string $path = ''): string {
    return BASE_PATH . '/' . ltrim($path, '/');
}

spl_autoload_register(function (string $class): void {
    foreach (['core', 'model', 'controller', 'app_admin/controller'] as $dir) {
        $file = ROOT . '/' . $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

Router::get('/', 'Home', 'index');
Router::get('/products', 'Product', 'index');
Router::get('/product', 'Product', 'detail');
Router::get('/cart', 'Product', 'cart');
Router::post('/cart', 'Product', 'cart');
Router::get('/checkout', 'Product', 'checkout');
Router::post('/checkout', 'Product', 'checkout');
Router::post('/process-checkout', 'Product', 'processCheckout');

Router::get('/my-orders', 'Account', 'orders');

Router::get('/login', 'Auth', 'showLogin');
Router::post('/login', 'Auth', 'login');
Router::get('/register', 'Auth', 'showRegister');
Router::post('/register', 'Auth', 'register');
Router::get('/logout', 'Auth', 'logout');

Router::get('/admin', 'Admin', 'dashboard');
Router::any('/admin/danhmuc', 'Admin', 'danhmuc');
Router::any('/admin/danhmucchitiet', 'Admin', 'danhmucChiTiet');
Router::any('/admin/mathang', 'Admin', 'mathang');
Router::any('/admin/donhang', 'Admin', 'donhang');
Router::get('/admin/get_images', 'Admin', 'get_images');
Router::post('/admin/upload', 'Admin', 'upload');

Router::dispatch();
