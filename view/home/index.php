<?php $activeNav = 'home'; ?>

<section class="container mb-4 mt-2">
    <div style="border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <img src="<?= url('view/images/panner(1).jpg') ?>" alt="SachKaka Banner" style="width: 100%; height: auto; display: block; object-fit: cover;">
    </div>
</section>

<section class="container mb-5 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Sản Phẩm Mới Nhất</h3>
        <a href="<?= url('products') ?>" class="text-decoration-none fw-bold" style="color:var(--primary-color)">Xem tất cả →</a>
    </div>
    <div class="row g-4">
        <?php foreach ($products as $product): ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card glass-panel book-card h-100 border-0">
                <a href="<?= url('product?id=' . $product['id']) ?>" class="text-decoration-none text-dark d-flex flex-column h-100">
                    <div class="book-img position-relative">
                        <?php if ($product['soluongton'] <= 0): ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.7); z-index: 10;">
                                <span class="badge bg-danger fs-6 px-3 py-2 shadow">Hết hàng</span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($product['hinhanh'])): ?>
                            <img src="<?= url($product['hinhanh']) ?>" alt="<?= htmlspecialchars($product['tenmathang']) ?>"
                                 style="width:100%;height:100%;object-fit:cover"
                                 onerror="this.parentNode.innerHTML='<div style=\'width:100%;height:100%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8\'><i class=\'fas fa-image fa-3x\'></i></div>'">
                        <?php else: ?>
                            <div style="width:100%;height:100%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8"><i class="fas fa-image fa-3x"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge bg-danger w-auto mb-2" style="align-self:flex-start;font-size:.7rem"><?= htmlspecialchars($product['tendanhmuc_chitiet']) ?></span>
                        <h6 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($product['tenmathang']) ?>"><?= htmlspecialchars($product['tenmathang']) ?></h6>
                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2">
                            <div>
                                <?php if ($product['giagoc'] > $product['giaban']): ?>
                                <small class="text-decoration-line-through text-muted d-block" style="font-size:.7rem"><?= number_format($product['giagoc'],0,',','.') ?> ₫</small>
                                <?php endif; ?>
                                <span class="fw-bold fs-6 text-danger-custom"><?= number_format($product['giaban'],0,',','.') ?> ₫</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle add-to-cart-btn" 
                                data-id="<?= $product['id'] ?>" 
                                data-name="<?= htmlspecialchars($product['tenmathang']) ?>" 
                                data-price="<?= $product['giaban'] ?>" 
                                data-img="<?= url($product['hinhanh'] ?? '') ?>" 
                                data-stock="<?= $product['soluongton'] ?>"
                                style="width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center;position:relative;z-index:2;"
                                <?= $product['soluongton'] <= 0 ? 'disabled' : '' ?>>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
