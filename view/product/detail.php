<?php $activeNav = 'products'; ?>

<div class="container my-5 flex-grow-1">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>" class="text-decoration-none text-danger-custom"><i class="fas fa-home"></i> Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= url('products?category=' . $product['danhmucchitiet_id']) ?>" class="text-decoration-none text-muted"><?= htmlspecialchars($product['tendanhmuc_cha']) ?></a></li>
            <li class="breadcrumb-item active fw-bold" aria-current="page"><?= htmlspecialchars($product['tenmathang']) ?></li>
        </ol>
    </nav>

    <div class="card glass-panel border-0 mb-5">
        <div class="card-body p-4 p-md-5">
            <div class="row g-5">
                <!-- Product Images -->
                <div class="col-md-5">
                    <div class="position-relative mb-3 bg-light rounded" style="aspect-ratio:1;display:flex;align-items:center;justify-content:center;overflow:hidden">
                        <?php if (!empty($product['hinhanh'])): ?>
                            <img id="mainImage" src="<?= url($product['hinhanh']) ?>" alt="<?= htmlspecialchars($product['tenmathang']) ?>" style="width:100%;height:100%;object-fit:cover">
                        <?php else: ?>
                            <i class="fas fa-image fa-5x text-muted opacity-25"></i>
                        <?php endif; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                    <div class="d-flex gap-2 overflow-auto py-2" id="thumbnailContainer">
                        <?php foreach ($images as $img): ?>
                        <div class="rounded border bg-light cursor-pointer thumbnail-item <?= $img['la_anh_chinh'] ? 'border-danger' : '' ?>" style="width:70px;height:70px;flex-shrink:0;overflow:hidden" onclick="changeMainImage('<?= url($img['duongdan']) ?>', this)">
                            <img src="<?= url($img['duongdan']) ?>" style="width:100%;height:100%;object-fit:cover">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="col-md-7">
                    <span class="badge bg-danger mb-2 fs-6 px-3 py-2"><?= htmlspecialchars($product['tendanhmuc_chitiet']) ?></span>
                    <h2 class="fw-bold mb-3"><?= htmlspecialchars($product['tenmathang']) ?></h2>
                    
                    <div class="d-flex align-items-baseline gap-3 mb-4">
                        <h3 class="fw-bold text-danger-custom mb-0"><?= number_format($product['giaban'], 0, ',', '.') ?> ₫</h3>
                        <?php if ($product['giagoc'] > $product['giaban']): ?>
                        <span class="text-decoration-line-through text-muted fs-5"><?= number_format($product['giagoc'], 0, ',', '.') ?> ₫</span>
                        <span class="badge bg-warning text-dark">-<?= round((1 - $product['giaban']/$product['giagoc'])*100) ?>%</span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted mb-1"><i class="fas fa-box me-2"></i>Tình trạng: 
                            <?php if ($product['soluongton'] > 0): ?>
                                <span class="text-success fw-bold">Còn hàng</span>
                            <?php else: ?>
                                <span class="text-danger fw-bold">Hết hàng</span>
                            <?php endif; ?>
                        </p>
                        <p class="text-muted mb-1"><i class="fas fa-eye me-2"></i>Lượt xem: <?= $product['luotxem'] ?></p>
                        <p class="text-muted"><i class="fas fa-shopping-cart me-2"></i>Đã bán: <?= $product['luotmua'] ?></p>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="input-group" style="width: 130px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="updateQty(-1)"><i class="fas fa-minus"></i></button>
                            <input type="text" class="form-control text-center text-dark fw-bold" id="buyQty" value="1" min="1" max="<?= $product['soluongton'] ?>" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="updateQty(1)"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-outline-danger btn-lg px-4 flex-grow-1" onclick="addToCartAndShow()" <?= $product['soluongton'] == 0 ? 'disabled' : '' ?>>
                            <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ
                        </button>
                        <button type="button" class="btn btn-danger btn-lg px-4 flex-grow-1" onclick="buyNow()" <?= $product['soluongton'] == 0 ? 'disabled' : '' ?>>
                            Mua ngay
                        </button>
                    </div>

                    <hr class="my-4">
                    
                    <h5 class="fw-bold mb-3">Mô tả sản phẩm</h5>
                    <div class="text-muted lh-lg" style="font-size: 0.95rem;">
                        <?= empty($product['mota']) ? '<p>Chưa có mô tả chi tiết cho sản phẩm này.</p>' : $product['mota'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (count($related) > 0): ?>
    <h4 class="fw-bold mb-4 mt-5 pt-3 border-top"><i class="fas fa-tags text-danger me-2"></i>Sản phẩm cùng danh mục</h4>
    <div class="row g-4">
        <?php foreach ($related as $rel): ?>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card glass-panel book-card h-100 border-0">
                <a href="<?= url('product?id=' . $rel['id']) ?>" class="text-decoration-none text-dark d-flex flex-column h-100">
                    <div class="book-img" style="height: 180px;">
                        <?php if (!empty($rel['hinhanh'])): ?>
                            <img src="<?= url($rel['hinhanh']) ?>" alt="<?= htmlspecialchars($rel['tenmathang']) ?>" style="width:100%;height:100%;object-fit:cover">
                        <?php else: ?>
                            <div style="width:100%;height:100%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8"><i class="fas fa-image fa-2x"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-2 d-flex flex-column">
                        <h6 class="card-title fw-bold text-truncate" style="font-size:0.85rem" title="<?= htmlspecialchars($rel['tenmathang']) ?>"><?= htmlspecialchars($rel['tenmathang']) ?></h6>
                        <div class="mt-auto pt-1">
                            <span class="fw-bold text-danger-custom" style="font-size:0.9rem"><?= number_format($rel['giaban'],0,',','.') ?> ₫</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<footer class="py-4 mt-5" style="background:#fff;border-top:1px solid var(--glass-border)">
    <div class="container text-center text-muted"><p class="mb-0">&copy; 2026 SachKaka.</p></div>
</footer>

<script>
const maxQty = <?= $product['soluongton'] ?>;
function updateQty(change) {
    const input = document.getElementById('buyQty');
    let val = parseInt(input.value) + change;
    if (val < 1) val = 1;
    if (val > maxQty) val = maxQty;
    input.value = val;
}

function changeMainImage(src, el) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumbnail-item').forEach(e => e.classList.remove('border-danger'));
    el.classList.add('border-danger');
}

function addToCartAndShow() {
    if (typeof IS_LOGGED_IN !== 'undefined' && !IS_LOGGED_IN) {
        window.location.href = '<?= url('login') ?>';
        return;
    }
    const qty = parseInt(document.getElementById('buyQty').value);
    addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['tenmathang'])) ?>', <?= $product['giaban'] ?>, '<?= url($product['hinhanh'] ?? '') ?>', qty, maxQty);
    alert('Đã thêm sản phẩm vào giỏ hàng!');
}

function buyNow() {
    if (typeof IS_LOGGED_IN !== 'undefined' && !IS_LOGGED_IN) {
        window.location.href = '<?= url('login') ?>';
        return;
    }
    const qty = parseInt(document.getElementById('buyQty').value);
    addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['tenmathang'])) ?>', <?= $product['giaban'] ?>, '<?= url($product['hinhanh'] ?? '') ?>', qty, maxQty);
    window.location.href = '<?= url('cart') ?>';
}
</script>
