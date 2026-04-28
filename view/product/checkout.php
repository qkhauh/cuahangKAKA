<?php 
$activeNav = ''; 
$u = $user ?? null;
?>

<div class="container my-5 flex-grow-1">
    <div class="mb-4 d-flex align-items-center">
        <a href="<?= url('cart') ?>" class="text-decoration-none text-muted me-3 fs-5"><i class="fas fa-arrow-left"></i></a>
        <h2 class="fw-bold mb-0">Thanh toán</h2>
    </div>

    <div class="row g-4">
        <!-- Form thông tin giao hàng -->
        <div class="col-lg-7">
            <div class="card glass-panel border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="fas fa-map-marker-alt text-danger-custom me-2"></i> Thông tin giao hàng</h5>
                    <form id="checkoutForm" onsubmit="handleCheckout(event)">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="hoten" value="<?= htmlspecialchars($u['hoten'] ?? '') ?>" required placeholder="Nhập họ và tên người nhận">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="sodienthoai" value="<?= htmlspecialchars($u['sodienthoai'] ?? '') ?>" required placeholder="Nhập số điện thoại">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($u['email'] ?? '') ?>" placeholder="Nhập email (tùy chọn)">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="diachi" rows="3" required placeholder="Nhập địa chỉ nhận hàng chi tiết"><?= htmlspecialchars($u['diachi'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Ghi chú đơn hàng</label>
                                <textarea class="form-control" name="ghichu" rows="2" placeholder="Ghi chú thêm về đơn hàng, thời gian nhận hàng..."></textarea>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3 mt-5"><i class="fas fa-credit-card text-danger-custom me-2"></i> Phương thức thanh toán</h5>
                        <div class="payment-methods">
                            <div class="form-check custom-payment-radio mb-3 p-3 border rounded bg-light">
                                <input class="form-check-input ms-1 mt-2" type="radio" name="payment_method" id="paymentCOD" value="cod" checked>
                                <label class="form-check-label ms-2 d-flex align-items-center" for="paymentCOD">
                                    <div class="ms-2">
                                        <div class="fw-bold">Thanh toán khi nhận hàng (COD)</div>
                                        <small class="text-muted">Bạn sẽ thanh toán bằng tiền mặt khi nhận được hàng.</small>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check custom-payment-radio mb-3 p-3 border rounded">
                                <input class="form-check-input ms-1 mt-2" type="radio" name="payment_method" id="paymentBank" value="bank">
                                <label class="form-check-label ms-2 d-flex align-items-center" for="paymentBank">
                                    <div class="ms-2">
                                        <div class="fw-bold">Chuyển khoản qua ngân hàng</div>
                                        <small class="text-muted">Chuyển khoản trực tiếp tới tài khoản của cửa hàng.</small>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold" id="btnSubmitOrder">
                                Xác nhận đặt hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div class="col-lg-5">
            <div class="card glass-panel border-0 sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Đơn hàng của bạn</h5>
                    <div id="checkoutItemsList" class="mb-4" style="max-height: 400px; overflow-y: auto;">
                        <!-- JS render -->
                    </div>
                    
                    <hr class="border-secondary opacity-25 mb-4">
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-bold fs-5" id="checkoutSubtotal">0 ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí giao hàng:</span>
                        <span class="fw-bold text-success">Miễn phí</span>
                    </div>
                    <hr class="border-secondary opacity-25 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold fs-5">Tổng cộng:</span>
                        <span class="fw-bold fs-3 text-danger-custom" id="checkoutTotal">0 ₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thành công -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow text-center p-4">
      <div class="modal-body">
        <div class="text-success mb-3">
            <i class="fas fa-check-circle" style="font-size: 5rem;"></i>
        </div>
        <h3 class="fw-bold mb-3">Đặt hàng thành công!</h3>
        <p class="text-muted mb-4">Cảm ơn bạn đã mua sắm tại SachKaka. Đơn hàng của bạn đang được xử lý và sẽ được giao trong thời gian sớm nhất.</p>
        <button type="button" class="btn btn-danger px-4 py-2 fw-bold" onclick="finishCheckout()">Trở về trang chủ</button>
      </div>
    </div>
  </div>
</div>

<footer class="py-4 mt-5" style="background:#fff;border-top:1px solid var(--glass-border)">
    <div class="container text-center text-muted"><p class="mb-0">&copy; 2026 SachKaka.</p></div>
</footer>

<style>
    .custom-payment-radio { transition: all 0.2s ease; cursor: pointer; }
    .custom-payment-radio:has(input:checked) {
        border-color: var(--primary-color) !important;
        background-color: rgba(220, 53, 69, 0.05) !important;
    }
</style>

<script>
let selectedCartItems = [];

function loadCheckoutItems() {
    const cart = getCart();
    selectedCartItems = cart.filter(item => item.selected);
    
    if (selectedCartItems.length === 0) {
        alert('Không có sản phẩm nào được chọn để thanh toán.');
        window.location.href = BASE_URL + '/cart';
        return;
    }
    
    const listContainer = document.getElementById('checkoutItemsList');
    let html = '';
    let total = 0;
    
    selectedCartItems.forEach(item => {
        const itemTotal = item.price * item.qty;
        total += itemTotal;
        html += `
            <div class="d-flex align-items-center mb-3">
                <div class="position-relative me-3">
                    <div class="rounded border bg-light" style="width: 60px; height: 60px; overflow: hidden;">
                        <img src="${item.img}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://placehold.co/60x60/f1f5f9/94a3b8?text=?'">
                    </div>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        ${item.qty}
                    </span>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <h6 class="mb-1 text-truncate" title="${item.name}">${item.name}</h6>
                    <small class="text-muted">${new Intl.NumberFormat('vi-VN').format(item.price)} ₫</small>
                </div>
                <div class="fw-bold ms-3">
                    ${new Intl.NumberFormat('vi-VN').format(itemTotal)} ₫
                </div>
            </div>
        `;
    });
    
    listContainer.innerHTML = html;
    
    const totalFormatted = new Intl.NumberFormat('vi-VN').format(total) + ' ₫';
    document.getElementById('checkoutSubtotal').innerText = totalFormatted;
    document.getElementById('checkoutTotal').innerText = totalFormatted;
}

function handleCheckout(e) {
    e.preventDefault();
    
    if (selectedCartItems.length === 0) {
        alert('Không có sản phẩm nào để thanh toán!');
        return;
    }
    
    const btn = document.getElementById('btnSubmitOrder');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Đang xử lý...';
    btn.disabled = true;
    
    // Lấy dữ liệu từ form
    const formData = new FormData(document.getElementById('checkoutForm'));
    const orderData = Object.fromEntries(formData.entries());
    
    // Gọi API để tạo đơn hàng và trừ số lượng sản phẩm
    fetch(BASE_URL + '/process-checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            items: selectedCartItems,
            order: orderData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Xóa các sản phẩm đã chọn khỏi giỏ hàng
            let cart = getCart();
            cart = cart.filter(item => !item.selected);
            saveCart(cart);
            
            // Hiển thị modal thành công
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể xử lý đơn hàng. Vui lòng thử lại.'));
            btn.innerHTML = 'Xác nhận đặt hàng';
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Đã xảy ra lỗi hệ thống, vui lòng thử lại sau.');
        btn.innerHTML = 'Xác nhận đặt hàng';
        btn.disabled = false;
    });
}

function finishCheckout() {
    window.location.href = BASE_URL;
}

document.addEventListener('DOMContentLoaded', () => {
    loadCheckoutItems();
    
    // Style cho các phương thức thanh toán
    document.querySelectorAll('.custom-payment-radio input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.custom-payment-radio').forEach(el => {
                el.classList.remove('bg-light');
            });
            if (this.checked) {
                this.closest('.custom-payment-radio').classList.add('bg-light');
            }
        });
    });
});
</script>
