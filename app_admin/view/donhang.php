<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Quản lý Đơn Hàng</h2>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Liên hệ</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có đơn hàng nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?= $order['id'] ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($order['hoten']) ?></div>
                                    <?php if ($order['nguoidung_id']): ?>
                                    <span class="badge bg-primary" style="font-size:0.6rem">Thành viên</span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary" style="font-size:0.6rem">Khách vãng lai</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div><i class="fas fa-phone-alt text-muted me-1" style="font-size:0.8rem"></i> <?= htmlspecialchars($order['sodienthoai']) ?></div>
                                    <div class="text-muted" style="font-size:0.85rem"><i class="fas fa-envelope me-1" style="font-size:0.8rem"></i> <?= htmlspecialchars($order['email'] ?: 'Không có') ?></div>
                                </td>
                                <td class="fw-bold text-danger"><?= number_format($order['tongtien'], 0, ',', '.') ?> ₫</td>
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
                                <td><?= date('d/m/Y H:i', strtotime($order['ngaytao'])) ?></td>
                                <td class="text-end pe-4">
                                    <a href="<?= url('admin/donhang?action=detail&id=' . $order['id']) ?>" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
