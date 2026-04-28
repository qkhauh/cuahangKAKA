<div class="container py-5 mt-5">
    <div class="mb-4">
        <a href="<?= url('my-orders') ?>" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Quay lại danh sách đơn hàng</a>
        <h2 class="fw-bold mb-0" style="color:var(--primary-color)">Chi tiết đơn hàng #<?= $order['id'] ?></h2>
    </div>

    <div class="row g-4">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-info-circle text-primary me-2"></i>Thông tin chung</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 120px;">Ngày đặt:</td>
                            <td class="fw-bold"><?= date('d/m/Y H:i', strtotime($order['ngaytao'])) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái:</td>
                            <td>
                                <?php
                                $badgeClass = 'bg-secondary';
                                if ($order['trangthai'] === 'chờ xử lý') $badgeClass = 'bg-warning text-dark';
                                elseif ($order['trangthai'] === 'đang giao') $badgeClass = 'bg-info text-dark';
                                elseif ($order['trangthai'] === 'đã giao') $badgeClass = 'bg-success';
                                elseif ($order['trangthai'] === 'đã hủy') $badgeClass = 'bg-danger';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($order['trangthai'])) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Thanh toán:</td>
                            <td class="fw-bold text-uppercase"><?= htmlspecialchars($order['phuongthucthanhtoan']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tổng tiền:</td>
                            <td class="fw-bold text-danger fs-5"><?= number_format($order['tongtien'], 0, ',', '.') ?> ₫</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin giao hàng -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-map-marker-alt text-success me-2"></i>Địa chỉ giao hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 120px;">Người nhận:</td>
                            <td class="fw-bold"><?= htmlspecialchars($order['hoten']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Điện thoại:</td>
                            <td class="fw-bold"><?= htmlspecialchars($order['sodienthoai']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Địa chỉ:</td>
                            <td><?= htmlspecialchars($order['diachi']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ghi chú:</td>
                            <td><?= htmlspecialchars($order['ghichu'] ?: 'Không có') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h5 class="fw-bold"><i class="fas fa-box-open text-danger me-2"></i>Sản phẩm đã mua</h5>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Sản phẩm</th>
                            <th class="py-3">Đơn giá</th>
                            <th class="text-center py-3">Số lượng</th>
                            <th class="text-end pe-4 py-3">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($details as $item): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded border bg-white" style="width: 60px; height: 60px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                        <img src="<?= url($item['hinhanh']) ?>" style="max-width:100%;max-height:100%;object-fit:contain" onerror="this.src='https://placehold.co/60x60/f1f5f9/94a3b8?text=?'">
                                    </div>
                                    <a href="<?= url('product?id=' . $item['mathang_id']) ?>" class="fw-bold text-dark text-decoration-none hover-danger">
                                        <?= htmlspecialchars($item['tenmathang']) ?>
                                    </a>
                                </div>
                            </td>
                            <td><?= number_format($item['dongia'], 0, ',', '.') ?> ₫</td>
                            <td class="text-center fw-bold"><?= $item['soluong'] ?></td>
                            <td class="text-end pe-4 fw-bold text-danger"><?= number_format($item['dongia'] * $item['soluong'], 0, ',', '.') ?> ₫</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
.hover-danger:hover { color: var(--primary-color) !important; }
</style>
