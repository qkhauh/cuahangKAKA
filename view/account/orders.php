<div class="container py-5 mt-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold" style="color:var(--primary-color)">ĐƠN HÀNG CỦA TÔI</h2>
            <div class="mx-auto mt-2" style="width:60px;height:4px;background-color:var(--primary-color);border-radius:2px"></div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <?php if (empty($orders)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-box-open text-muted mb-3" style="font-size:4rem;opacity:0.5"></i>
                        <h5 class="text-muted">Bạn chưa có đơn hàng nào</h5>
                        <p class="text-muted mb-4">Hãy tiếp tục khám phá và mua sắm những cuốn sách hay nhé!</p>
                        <a href="<?= url('products') ?>" class="btn btn-danger-custom px-4 py-2" style="border-radius:8px">Tiếp tục mua sắm</a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Mã Đơn</th>
                                    <th class="py-3">Ngày Đặt</th>
                                    <th class="py-3">Tổng Tiền</th>
                                    <th class="py-3">Trạng Thái</th>
                                    <th class="pe-4 py-3 text-end">Chi Tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#<?= $order['id'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($order['ngaytao'])) ?></td>
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
                                    <td class="pe-4 text-end">
                                        <a href="<?= url('my-orders?action=detail&id=' . $order['id']) ?>" class="btn btn-sm btn-outline-primary" style="border-radius:6px">
                                            Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
