<?php
class Account extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect(url('login'));
            return;
        }
    }

    public function orders(): void {
        require_once 'model/DonHangModel.php';
        $model = new DonHangModel();
        
        $userId = (int)$_SESSION['user_id'];
        $action = $_GET['action'] ?? 'list';
        
        if ($action === 'detail') {
            $id = (int)($_GET['id'] ?? 0);
            $order = $model->getById($id);
            // Verify order belongs to user
            if (!$order || $order['nguoidung_id'] != $userId) {
                $this->redirect(url('my-orders'));
                return;
            }
            
            $details = $model->getDetails($id);
            $this->render('account/order_detail', [
                'pageTitle' => 'Chi tiết đơn hàng - SachKaka',
                'categories' => (new DanhMucModel())->getWithDetails(),
                'order' => $order,
                'details' => $details
            ]);
            return;
        }

        $orders = $model->getByUserId($userId);
        
        $this->render('account/orders', [
            'pageTitle' => 'Đơn hàng của tôi - SachKaka',
            'categories' => (new DanhMucModel())->getWithDetails(),
            'orders' => $orders
        ]);
    }
}
