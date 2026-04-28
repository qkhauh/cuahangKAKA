<div class="p-4">
    <div class="mb-4">
        <a href="<?= url('admin/donhang') ?>" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Quay lại danh sách</a>
        <h2 class="fw-bold mb-0">Chi tiết đơn hàng #<?= $order['id'] ?></h2>
    </div>

    <div class="row g-4">
        <!-- Thông tin khách hàng -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-user-circle text-primary me-2"></i>Thông tin Khách hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 100px;">Họ tên:</td>
                            <td class="fw-bold"><?= htmlspecialchars($order['hoten']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Điện thoại:</td>
                            <td class="fw-bold"><?= htmlspecialchars($order['sodienthoai']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td><?= htmlspecialchars($order['email'] ?: 'Không có') ?></td>
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

        <!-- Thông tin Đơn hàng -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-receipt text-success me-2"></i>Thông tin Đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 120px;">Ngày đặt:</td>
                            <td class="fw-bold"><?= date('d/m/Y H:i', strtotime($order['ngaytao'])) ?></td>
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

                    <hr>
                    
                    <form action="<?= url('admin/donhang') ?>" method="POST" class="mt-3">
                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <label class="form-label text-muted fw-bold">Cập nhật trạng thái:</label>
                        <div class="input-group">
                            <select name="trangthai" class="form-select">
                                <option value="chờ xử lý" <?= $order['trangthai'] === 'chờ xử lý' ? 'selected' : '' ?>>Chờ xử lý</option>
                                <option value="đang giao" <?= $order['trangthai'] === 'đang giao' ? 'selected' : '' ?>>Đang giao</option>
                                <option value="đã giao" <?= $order['trangthai'] === 'đã giao' ? 'selected' : '' ?>>Đã giao thành công</option>
                                <option value="đã hủy" <?= $order['trangthai'] === 'đã hủy' ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
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
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end pe-4">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($details as $item): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded border bg-light" style="width: 50px; height: 50px; overflow: hidden;">
                                        <img src="<?= url($item['hinhanh']) ?>" style="width:100%;height:100%;object-fit:cover" onerror="this.src='https://placehold.co/50x50/f1f5f9/94a3b8?text=?'">
                                    </div>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($item['tenmathang']) ?></span>
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
