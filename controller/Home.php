<?php
class Home extends Controller {
    public function index(): void {
        $danhMucModel = new DanhMucModel();
        $matHangModel = new MatHangModel();
        
        $this->render('home/index', [
            'pageTitle' => 'Trang Chủ - SachKaka',
            'categories' => $danhMucModel->getWithDetails(),
            'products' => $matHangModel->getLatest(8),
        ]);
    }
}
