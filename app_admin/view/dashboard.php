<?php $activeAdmin = 'dashboard'; ?>
<div class="p-4">
    <nav class="navbar bg-white shadow-sm rounded-3 px-3 mb-4">
        <span class="fw-bold">Xin chào, Admin <?= htmlspecialchars($_SESSION['user_name']) ?></span>
    </nav>
    <h2 class="fw-bold mb-4">Tổng Quan Hệ Thống</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h6 class="text-muted">Tổng Danh Mục</h6>
                    <h2 class="fw-bold"><?= $count_danhmuc ?></h2>
                    <a href="<?= url('admin/danhmuc') ?>" class="btn btn-sm btn-outline-danger mt-2">Quản lý →</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h6 class="text-muted">Tổng Danh Mục Sản Phẩm</h6>
                    <h2 class="fw-bold"><?= $count_chitiet ?></h2>
                    <a href="<?= url('admin/danhmucchitiet') ?>" class="btn btn-sm btn-outline-danger mt-2">Quản lý →</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h6 class="text-muted">Tổng Mặt Hàng</h6>
                    <h2 class="fw-bold"><?= $count_mathang ?></h2>
                    <a href="<?= url('admin/mathang') ?>" class="btn btn-sm btn-outline-danger mt-2">Quản lý →</a>
                </div>
            </div>
        </div>
    </div>
</div>
