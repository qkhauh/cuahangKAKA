<?php $activeNav = ''; ?>

<div class="container my-5 flex-grow-1">
    <h2 class="fw-bold mb-4">Giỏ hàng của bạn</h2>

    <div class="row g-4" id="cartContainer" style="display: none;">
        <div class="col-lg-8">
            <div class="card glass-panel border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 50px;">
                                        <input class="form-check-input fs-5" type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" title="Chọn tất cả">
                                    </th>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th style="width: 140px;">Số lượng</th>
                                    <th>Tạm tính</th>
                                    <th class="text-end pe-4"></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- Rendered by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card glass-panel border-0 sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Tóm tắt đơn hàng</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-bold fs-5" id="cartSubtotal">0 ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Phí giao hàng:</span>
                        <span class="text-muted">Chưa tính</span>
                    </div>
                    <hr class="border-secondary opacity-25 mb-4">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Tổng cộng:</span>
                        <span class="fw-bold fs-4 text-danger-custom" id="cartTotal">0 ₫</span>
                    </div>
                    <button class="btn btn-danger w-100 btn-lg fw-bold" onclick="proceedToCheckout()">
                        Tiến hành thanh toán (<span id="cartCountSelected">0</span>)
                    </button>
                    <div class="text-center mt-3">
                        <a href="<?= url('products') ?>" class="text-decoration-none text-danger-custom"><i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="emptyCartMessage" class="text-center py-5" style="display: none;">
        <i class="fas fa-shopping-basket fa-4x text-muted mb-4 opacity-50"></i>
        <h4 class="fw-bold text-muted mb-3">Giỏ hàng của bạn đang trống</h4>
        <p class="text-muted mb-4">Có vẻ như bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
        <a href="<?= url('products') ?>" class="btn btn-danger px-4 py-2 rounded-pill">Khám phá sản phẩm ngay</a>
    </div>
</div>

<footer class="py-4 mt-5" style="background:#fff;border-top:1px solid var(--glass-border)">
    <div class="container text-center text-muted"><p class="mb-0">&copy; 2026 SachKaka.</p></div>
</footer>

<script>
function renderCartPage() {
    const cart = getCart();
    const container = document.getElementById('cartContainer');
    const emptyMsg = document.getElementById('emptyCartMessage');
    const tbody = document.getElementById('cartTableBody');
    
    if (cart.length === 0) {
        container.style.display = 'none';
        emptyMsg.style.display = 'block';
        return;
    }
    
    container.style.display = 'flex';
    emptyMsg.style.display = 'none';
    
    let html = '';
    let total = 0;
    let selectedCount = 0;
    let allSelected = true;
    
    cart.forEach((item, index) => {
        // Mặc định là chọn nếu chưa có thuộc tính selected
        if (typeof item.selected === 'undefined') item.selected = true;
        
        const itemTotal = item.price * item.qty;
        if (item.selected) {
            total += itemTotal;
            selectedCount++;
        } else {
            allSelected = false;
        }
        
        html += `
            <tr class="${item.selected ? '' : 'opacity-75'}">
                <td class="ps-4">
                    <input class="form-check-input fs-5 item-checkbox" type="checkbox" 
                           ${item.selected ? 'checked' : ''} 
                           onchange="toggleSelectItem(${index}, this.checked)">
                </td>
                <td class="py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded border bg-light" style="width: 60px; height: 60px; overflow: hidden; flex-shrink: 0;">
                            <img src="${item.img}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://placehold.co/60x60/f1f5f9/94a3b8?text=?'">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-dark text-decoration-none d-block text-truncate" style="max-width: 200px;" title="${item.name}">${item.name}</h6>
                        </div>
                    </div>
                </td>
                <td class="fw-bold text-muted">${new Intl.NumberFormat('vi-VN').format(item.price)} ₫</td>
                <td>
                    <div class="input-group input-group-sm" style="width: 100px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="updateCartItemQty(${index}, -1)"><i class="fas fa-minus"></i></button>
                        <input type="text" class="form-control text-center fw-bold bg-white" value="${item.qty}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="updateCartItemQty(${index}, 1)"><i class="fas fa-plus"></i></button>
                    </div>
                </td>
                <td class="fw-bold text-danger-custom">${new Intl.NumberFormat('vi-VN').format(itemTotal)} ₫</td>
                <td class="text-end pe-4">
                    <button class="btn btn-sm btn-light text-danger rounded-circle" style="width:32px;height:32px;" onclick="removeCartItem(${index})" title="Xóa khỏi giỏ">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
    
    document.getElementById('selectAllCheckbox').checked = allSelected && cart.length > 0;
    
    const totalFormatted = new Intl.NumberFormat('vi-VN').format(total) + ' ₫';
    document.getElementById('cartSubtotal').innerText = totalFormatted;
    document.getElementById('cartTotal').innerText = totalFormatted;
    document.getElementById('cartCountSelected').innerText = selectedCount;
}

function updateCartItemQty(index, change) {
    const cart = getCart();
    if (cart[index]) {
        const maxQty = cart[index].maxQty || 999;
        cart[index].qty += change;
        if (cart[index].qty < 1) cart[index].qty = 1;
        if (cart[index].qty > maxQty) {
            cart[index].qty = maxQty;
            alert('Sản phẩm này chỉ còn ' + maxQty + ' sản phẩm trong kho.');
        }
        saveCart(cart);
        renderCartPage();
    }
}

function removeCartItem(index) {
    if (confirm('Bạn có chắc muốn bỏ sản phẩm này khỏi giỏ hàng?')) {
        const cart = getCart();
        cart.splice(index, 1);
        saveCart(cart);
        renderCartPage();
    }
}

function toggleSelectItem(index, isChecked) {
    const cart = getCart();
    if (cart[index]) {
        cart[index].selected = isChecked;
        saveCart(cart);
        renderCartPage();
    }
}

function toggleSelectAll(checkbox) {
    const isChecked = checkbox.checked;
    const cart = getCart();
    cart.forEach(item => { item.selected = isChecked; });
    saveCart(cart);
    renderCartPage();
}

function proceedToCheckout() {
    const cart = getCart();
    const hasSelected = cart.some(item => item.selected);
    if (!hasSelected) {
        alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        return;
    }
    window.location.href = BASE_URL + '/checkout';
}

document.addEventListener('DOMContentLoaded', renderCartPage);
</script>
