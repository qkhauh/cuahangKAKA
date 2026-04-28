<?php
class Product extends Controller {
    public function index(): void {
        $categoryId = $_GET['category'] ?? null;
        $q = $_GET['q'] ?? null;
        $danhMucModel = new DanhMucModel();
        $matHangModel = new MatHangModel();

        $breadcrumb = null;
        if ($categoryId) {
            $s = (new DanhMucChiTietModel())->getAllWithParent();
            foreach ($s as $cat) {
                if ($cat['id'] == $categoryId) { $breadcrumb = $cat; break; }
            }
        }

        $this->render('product/index', [
            'pageTitle' => $breadcrumb ? htmlspecialchars($breadcrumb['tendanhmuc']) . ' - SachKaka' : 'Sản Phẩm - SachKaka',
            'categories' => $danhMucModel->getWithDetails(),
            'products' => $matHangModel->getFiltered($categoryId, $q),
            'q' => $q,
            'category' => $categoryId,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function detail(): void {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { $this->redirect(url('products')); return; }

        $matHangModel = new MatHangModel();
        $product = $matHangModel->getById($id);
        if (!$product) { $this->redirect(url('products')); return; }

        $images  = (new HinhAnhModel())->getByMatHang($id);
        $related = $matHangModel->getRelated($product['danhmucchitiet_id'], $id);

        (Database::getInstance())->prepare("UPDATE mathang SET luotxem = luotxem + 1 WHERE id = ?")
            ->execute([$id]);

        $this->render('product/detail', [
            'pageTitle' => htmlspecialchars($product['tenmathang']) . ' - SachKaka',
            'categories' => (new DanhMucModel())->getWithDetails(),
            'product' => $product,
            'images' => $images,
            'related' => $related,
        ]);
    }

    public function cart(): void {
        $this->render('product/cart', [
            'pageTitle' => 'Giỏ Hàng - SachKaka',
            'categories' => (new DanhMucModel())->getWithDetails(),
        ]);
    }

    public function checkout(): void {
        $user = null;
        if (isset($_SESSION['user_id'])) {
            require_once 'model/NguoiDungModel.php';
            $user = (new NguoiDungModel())->getById($_SESSION['user_id']);
        }

        $this->render('product/checkout', [
            'pageTitle' => 'Thanh Toán - SachKaka',
            'categories' => (new DanhMucModel())->getWithDetails(),
            'user' => $user,
        ]);
    }

    public function processCheckout(): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['items']) || !is_array($data['items']) || !isset($data['order'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
            return;
        }

        require_once 'model/MatHangModel.php';
        require_once 'model/DonHangModel.php';
        $donHangModel = new DonHangModel();
        
        try {
            // Tính tổng tiền
            $tongtien = 0;
            foreach ($data['items'] as $item) {
                $tongtien += $item['qty'] * $item['price'];
            }

            $orderData = [
                'nguoidung_id' => $_SESSION['user_id'] ?? null,
                'hoten' => trim($data['order']['hoten'] ?? ''),
                'sodienthoai' => trim($data['order']['sodienthoai'] ?? ''),
                'email' => trim($data['order']['email'] ?? ''),
                'diachi' => trim($data['order']['diachi'] ?? ''),
                'ghichu' => trim($data['order']['ghichu'] ?? ''),
                'tongtien' => $tongtien,
                'phuongthucthanhtoan' => trim($data['order']['payment_method'] ?? 'cod')
            ];

            if (empty($orderData['hoten']) || empty($orderData['sodienthoai']) || empty($orderData['diachi'])) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin giao hàng bắt buộc.']);
                return;
            }

            // Tạo đơn hàng (đã bao gồm trừ kho trong model)
            $orderId = $donHangModel->createOrder($orderData, $data['items']);
            
            echo json_encode(['success' => true, 'order_id' => $orderId]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }
}
