<?php
class Admin extends Controller {

    public function __construct() {
        $this->requireAdmin();
    }

    protected function render(string $view, array $data = [], string $layout = 'admin'): void {
        extract($data);
        ob_start();
        // Cắt bỏ chuỗi "admin/" nếu có để tìm đúng đường dẫn admin/view/{$view}.php
        $viewPath = str_replace('admin/', '', $view);
        require ROOT . '/app_admin/view/' . $viewPath . '.php';
        $content = ob_get_clean();
        require ROOT . '/app_admin/view/layout/' . $layout . '.php';
    }

    public function dashboard(): void {
        $counts = (new MatHangModel())->getDashboardCounts();
        $this->render('admin/dashboard', [
            'pageTitle'      => 'Quản Trị - SachKaka',
            'count_danhmuc'  => $counts['danhmuc'],
            'count_chitiet'  => $counts['chitiet'],
            'count_mathang'  => $counts['mathang'],
        ], 'admin');
    }

    public function danhmuc(): void {
        $model = new DanhMucModel();
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add']))    { $model->create(trim($_POST['tendanhmuc'])); $message = 'Thêm thành công!'; }
            elseif (isset($_POST['edit']))   { $model->update((int)$_POST['id'], trim($_POST['tendanhmuc'])); $message = 'Cập nhật thành công!'; }
            elseif (isset($_POST['delete'])) { 
                $id = (int)$_POST['id'];
                if ((new DanhMucChiTietModel())->countByDanhMuc($id) > 0) {
                    $message = 'Lỗi: Không thể xóa vì danh mục đang có danh mục sản phẩm con!';
                } else {
                    $model->delete($id); $message = 'Xóa thành công!'; 
                }
            }
        }
        $this->render('admin/danhmuc', ['pageTitle' => 'Danh Mục - Admin', 'items' => $model->getWithDetails(), 'message' => $message], 'admin');
    }

    public function danhmucChiTiet(): void {
        $model = new DanhMucChiTietModel();
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add']))    { $model->create(trim($_POST['tendanhmuc']), (int)$_POST['danhmuc_id']); $message = 'Thêm thành công!'; }
            elseif (isset($_POST['edit']))   { $model->update((int)$_POST['id'], trim($_POST['tendanhmuc']), (int)$_POST['danhmuc_id']); $message = 'Cập nhật thành công!'; }
            elseif (isset($_POST['delete'])) {
                $id = (int)$_POST['id'];
                if ((new MatHangModel())->countByDanhMucChiTiet($id) > 0) {
                    $message = 'Lỗi: Không thể xóa vì danh mục đang có chứa mặt hàng!';
                } else {
                    $model->delete($id); $message = 'Xóa thành công!';
                }
            }
        }
        $this->render('admin/danhmucchitiet', [
            'pageTitle'   => 'Danh Mục Chi Tiết - Admin',
            'items'       => $model->getAll(),
            'parent_cats' => $model->getAllParents(),
            'message'     => $message,
        ], 'admin');
    }

    public function mathang(): void {
        $model   = new MatHangModel();
        $imgModel = new HinhAnhModel();
        $message = '';
        $categoryId = $_GET['category'] ?? null;
        $q          = $_GET['q'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add'])) {
                $newId = $model->create([
                    'tenmathang'       => trim($_POST['tenmathang']),
                    'mota'             => trim($_POST['mota'] ?? ''),
                    'giagoc'           => $_POST['giagoc'] ?: 0,
                    'giaban'           => $_POST['giaban'] ?: 0,
                    'soluongton'       => $_POST['soluongton'] ?: 0,
                    'danhmucchitiet_id'=> $_POST['danhmucchitiet_id'],
                ]);
                $paths = json_decode($_POST['uploaded_images'] ?? '[]', true);
                if (!empty($paths)) $imgModel->linkTempImages($newId, $paths);
                $message = "Thêm mặt hàng thành công! <a href='" . url('admin/hinhanh?id=' . $newId) . "' class='alert-link'>Quản lý ảnh →</a>";
            } elseif (isset($_POST['edit'])) {
                $id = (int)$_POST['id'];
                $model->update($id, [
                    'tenmathang'       => trim($_POST['tenmathang']),
                    'mota'             => trim($_POST['mota'] ?? ''),
                    'giagoc'           => $_POST['giagoc'] ?: 0,
                    'giaban'           => $_POST['giaban'] ?: 0,
                    'soluongton'       => $_POST['soluongton'] ?: 0,
                    'danhmucchitiet_id'=> $_POST['danhmucchitiet_id'],
                ]);
                $message = 'Cập nhật thành công!';
            } elseif (isset($_POST['delete'])) {
                $id   = (int)$_POST['id'];
                $imgs = $imgModel->getByMatHang($id);
                foreach ($imgs as $img) {
                    $fp = ROOT . '/' . $img['duongdan'];
                    if (file_exists($fp)) @unlink($fp);
                }
                $imgModel->deleteByMatHang($id);
                $model->delete($id);
                $message = 'Xóa thành công!';
            }
        }
        $this->render('admin/mathang', [
            'pageTitle'   => 'Mặt Hàng - Admin',
            'items'       => $model->getFiltered($categoryId, $q),
            'detail_cats' => (new DanhMucChiTietModel())->getAllWithParent(),
            'message'     => $message,
            'category'    => $categoryId,
            'q'           => $q,
        ], 'admin');
    }

    public function donhang(): void {
        require_once 'model/DonHangModel.php';
        $model = new DonHangModel();
        
        $action = $_GET['action'] ?? 'list';
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            $id = (int)$_POST['id'];
            $status = trim($_POST['trangthai']);
            Database::getInstance()->prepare("UPDATE donhang SET trangthai = ? WHERE id = ?")->execute([$status, $id]);
            $message = 'Cập nhật trạng thái đơn hàng thành công!';
        }

        if ($action === 'detail') {
            $id = (int)($_GET['id'] ?? 0);
            $order = $model->getById($id);
            if (!$order) {
                $this->redirect(url('admin/donhang'));
                return;
            }
            $details = $model->getDetails($id);
            $this->render('admin/donhang_chitiet', [
                'pageTitle' => 'Chi tiết đơn hàng - Admin',
                'order' => $order,
                'details' => $details
            ], 'admin');
            return;
        }

        // list
        $orders = $model->getAll();
        $this->render('admin/donhang', [
            'pageTitle' => 'Quản lý Đơn hàng - Admin',
            'orders' => $orders,
            'message' => $message
        ], 'admin');
    }

    public function get_images(): void {
        $id = (int)($_GET['id'] ?? 0);
        $images = (new HinhAnhModel())->getByMatHang($id);
        $this->json(['success' => true, 'images' => $images]);
    }

    public function hinhanh(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { $this->redirect(url('admin/mathang')); return; }
        $mathang = (new MatHangModel())->getById($id);
        if (!$mathang) { $this->redirect(url('admin/mathang')); return; }
        $this->render('admin/hinhanh', [
            'pageTitle' => 'Hình Ảnh - Admin',
            'mathang'   => $mathang,
            'images'    => (new HinhAnhModel())->getByMatHang($id),
        ], 'admin');
    }

    public function upload(): void {
        $action    = $_POST['action'] ?? 'upload';
        $imgModel  = new HinhAnhModel();
        $uploadDir = ROOT . '/view/images/products/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $allowed = ['image/jpeg','image/jpg','image/png','image/webp'];
        $maxSize = 5 * 1024 * 1024;

        if ($action === 'delete') {
            $id = (int)($_POST['hinhanh_id'] ?? 0);
            $img = $imgModel->getById($id);
            if (!$img) { $this->json(['success'=>false,'message'=>'Không tìm thấy ảnh trên hệ thống.']); }
            
            $fp = ROOT . '/' . $img['duongdan'];
            if (file_exists($fp)) {
                @unlink($fp);
            }
            
            $imgModel->delete($id);
            if ($img['la_anh_chinh']) $imgModel->syncMainToProduct($img['mathang_id']);
            $this->json(['success'=>true, 'message'=>'Đã xóa ảnh thành công.']);
        }

        if ($action === 'set_main') {
            $img = $imgModel->getById((int)($_POST['hinhanh_id'] ?? 0));
            if (!$img) { $this->json(['success'=>false,'message'=>'Không tìm thấy ảnh.']); }
            $imgModel->setMain($img['id'], $img['mathang_id']);
            $imgModel->syncMainToProduct($img['mathang_id']);
            $this->json(['success'=>true]);
        }

        if ($action === 'upload_temp') {
            $result = $this->handleFileUploads('temp_', 0, $uploadDir, $allowed, $maxSize);
            $this->json(['success'=>!empty($result['paths']), 'temp_paths'=>$result['paths'], 'errors'=>$result['errors']]);
        }

        if ($action === 'upload') {
            $mathangId = (int)($_POST['mathang_id'] ?? 0);
            if ($mathangId <= 0) { $this->json(['success'=>false,'message'=>'ID sản phẩm không hợp lệ.']); }
            $existing = $imgModel->countByMatHang($mathangId);
            $result   = $this->handleFileUploads('product_' . $mathangId . '_', $mathangId, $uploadDir, $allowed, $maxSize);
            $uploaded = [];
            foreach ($result['paths'] as $i => $path) {
                $isMain = ($existing === 0 && $i === 0) ? 1 : 0;
                $newId  = $imgModel->create($mathangId, $path, $isMain, $existing + $i);
                if ($isMain) $imgModel->syncMainToProduct($mathangId);
                $uploaded[] = ['id'=>$newId,'duongdan'=>$path,'la_anh_chinh'=>$isMain,'thu_tu'=>$existing+$i];
            }
            $this->json(['success'=>!empty($uploaded),'uploaded'=>$uploaded,'errors'=>$result['errors']]);
        }

        $this->json(['success'=>false,'message'=>'Hành động không hợp lệ.']);
    }

    private function handleFileUploads(string $prefix, int $mathangId, string $dir, array $allowed, int $maxSize): array {
        $paths = []; $errors = [];
        if (empty($_FILES['files'])) return ['paths'=>[],'errors'=>['Không có file.']];

        $files = is_array($_FILES['files']['name'])
            ? array_map(fn($k) => array_column($_FILES['files'], $k), array_keys($_FILES['files']['name']))
            : [array_column($_FILES['files'], 0)];

        $list = [];
        if (is_array($_FILES['files']['name'])) {
            for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
                $list[] = ['name'=>$_FILES['files']['name'][$i],'type'=>$_FILES['files']['type'][$i],
                           'tmp_name'=>$_FILES['files']['tmp_name'][$i],'error'=>$_FILES['files']['error'][$i],
                           'size'=>$_FILES['files']['size'][$i]];
            }
        } else {
            $list[] = $_FILES['files'];
        }

        foreach ($list as $file) {
            if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > $maxSize) { $errors[] = $file['name']; continue; }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if (!in_array($mime, $allowed)) { $errors[] = $file['name'] . ' (không hợp lệ)'; continue; }
            $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $name = $prefix . uniqid() . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $dir . $name)) {
                $paths[] = 'view/images/products/' . $name;
            }
        }
        return ['paths'=>$paths,'errors'=>$errors];
    }
}
