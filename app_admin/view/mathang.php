<?php $activeAdmin = 'mathang'; ?>
<style>
.prod-thumb{width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0}
.img-count-badge{font-size:.65rem;position:absolute;top:-4px;right:-4px;padding:1px 5px}
.drop-zone{border:2px dashed #cbd5e1;border-radius:12px;padding:28px 16px;text-align:center;cursor:pointer;transition:all .2s;background:#f8fafc}
.drop-zone.dragover{border-color:#dc2626;background:#fff5f5}
.preview-grid{display:flex;flex-wrap:wrap;gap:10px;margin-top:12px}
.preview-item{position:relative;width:90px;height:90px;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)}
.preview-item img{width:100%;height:100%;object-fit:cover}
.preview-item .remove-btn{position:absolute;top:3px;right:3px;background:rgba(220,38,38,.85);border:none;border-radius:50%;width:22px;height:22px;color:white;font-size:.65rem;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:10}
.preview-item.main-badge::after{content:'★ Chính';position:absolute;bottom:0;left:0;right:0;background:rgba(220,38,38,.85);color:white;font-size:.6rem;text-align:center;padding:2px 0}
</style>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Quản Lý Mặt Hàng</h2>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-2"></i>Thêm Mới
        </button>
    </div>

    <?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?= url('admin/mathang') ?>" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="q" class="form-control" placeholder="Tìm kiếm tên mặt hàng..." value="<?= htmlspecialchars($q ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">-- Tất Cả Danh Mục Sản Phẩm --</option>
                        <?php foreach ($detail_cats as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($category??'')==$cat['id']?'selected':'' ?>>
                            <?= htmlspecialchars($cat['tendanhmuc_cha']) ?> › <?= htmlspecialchars($cat['tendanhmuc']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i>Tìm Kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>ID</th><th>Hình</th><th>Tên Mặt Hàng</th><th>Danh Mục</th><th>Giá Bán</th><th>Tồn Kho</th><th class="text-end">Hành Động</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td>
                                <div class="position-relative d-inline-block">
                                    <img src="<?= url($item['hinhanh'] ?? '') ?>" class="prod-thumb"
                                         onerror="this.src='https://placehold.co/48x48/f1f5f9/94a3b8?text=?'" alt="">
                                    <?php if ($item['so_anh'] > 0): ?>
                                    <span class="badge bg-danger img-count-badge"><?= $item['so_anh'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-truncate" style="max-width:220px" title="<?= htmlspecialchars($item['tenmathang']) ?>"><?= htmlspecialchars($item['tenmathang']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($item['tendanhmuc_chitiet']) ?></span></td>
                            <td class="text-danger fw-bold"><?= number_format($item['giaban'],0,',','.') ?> ₫</td>
                            <td><?= $item['soluongton'] ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="editMatHang(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['tenmathang'])) ?>', <?= $item['giagoc'] ?>, <?= $item['giaban'] ?>, <?= $item['soluongton'] ?>, <?= $item['danhmucchitiet_id'] ?>, '<?= htmlspecialchars(addslashes($item['mota'])) ?>')" data-bs-toggle="modal" data-bs-target="#editModal" title="Sửa"><i class="fas fa-edit"></i></button>
                                <form method="POST" action="" class="d-inline"
                                      onsubmit="return confirmFormSubmit(this, 'Bạn có chắc chắn muốn xóa mặt hàng này?')">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="delete" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form method="POST" action="<?= url('admin/mathang') ?>" class="modal-content" id="addForm">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle text-danger me-2"></i>Thêm Mặt Hàng Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Tên Mặt Hàng <span class="text-danger">*</span></label>
                        <input type="text" name="tenmathang" class="form-control" required placeholder="Nhập tên sản phẩm...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Danh Mục <span class="text-danger">*</span></label>
                        <select name="danhmucchitiet_id" class="form-select" required>
                            <?php foreach ($detail_cats as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['tendanhmuc_cha']) ?> › <?= htmlspecialchars($cat['tendanhmuc']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá Gốc (₫)</label>
                        <input type="number" name="giagoc" class="form-control" min="0" placeholder="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá Bán (₫) <span class="text-danger">*</span></label>
                        <input type="number" name="giaban" class="form-control" min="0" required placeholder="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Số Lượng Tồn Kho</label>
                        <input type="number" name="soluongton" class="form-control" min="0" value="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô Tả</label>
                    <textarea name="mota" class="form-control" rows="3" placeholder="Mô tả sản phẩm..."></textarea>
                </div>
                <hr>
                <label class="form-label fw-semibold"><i class="fas fa-images text-danger me-2"></i>Hình Ảnh Sản Phẩm</label>
                <p class="text-muted small mb-2">Ảnh đầu tiên sẽ là ảnh đại diện. JPG, PNG, WebP — tối đa 5MB/ảnh.</p>
                <div class="drop-zone" id="modalDropZone">
                    <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                    <p class="mb-1 mt-2 fw-semibold text-secondary">Kéo & thả ảnh vào đây</p>
                    <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                            onclick="document.getElementById('modalFileInput').click()">
                        <i class="fas fa-folder-open me-1"></i>Chọn Ảnh
                    </button>
                    <input type="file" id="modalFileInput" multiple accept="image/jpeg,image/png,image/webp" style="display:none">
                </div>
                <div class="preview-grid" id="previewGrid"></div>
                <input type="hidden" name="uploaded_images" id="uploadedImagesField" value="[]">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" name="add" class="btn btn-danger px-4" id="submitBtn">
                    <i class="fas fa-save me-2"></i>Lưu Sản Phẩm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form method="POST" action="<?= url('admin/mathang') ?>" class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit text-primary me-2"></i>Sửa Mặt Hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Tên Mặt Hàng <span class="text-danger">*</span></label>
                        <input type="text" name="tenmathang" id="edit_tenmathang" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Danh Mục <span class="text-danger">*</span></label>
                        <select name="danhmucchitiet_id" id="edit_danhmucchitiet_id" class="form-select" required>
                            <?php foreach ($detail_cats as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['tendanhmuc_cha']) ?> › <?= htmlspecialchars($cat['tendanhmuc']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá Gốc (₫)</label>
                        <input type="number" name="giagoc" id="edit_giagoc" class="form-control" min="0" placeholder="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá Bán (₫) <span class="text-danger">*</span></label>
                        <input type="number" name="giaban" id="edit_giaban" class="form-control" min="0" required placeholder="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Số Lượng Tồn Kho</label>
                        <input type="number" name="soluongton" id="edit_soluongton" class="form-control" min="0" value="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô Tả</label>
                    <textarea name="mota" id="edit_mota" class="form-control" rows="3"></textarea>
                </div>
                <hr>
                <label class="form-label fw-semibold"><i class="fas fa-images text-danger me-2"></i>Quản Lý Hình Ảnh</label>
                
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body p-3">
                        <div class="drop-zone py-3" id="editModalDropZone">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <p class="mb-1 fw-semibold text-secondary">Thêm ảnh mới</p>
                            <button type="button" class="btn btn-sm btn-outline-danger mt-1" onclick="document.getElementById('editModalFileInput').click()">Chọn Ảnh</button>
                            <input type="file" id="editModalFileInput" multiple accept="image/jpeg,image/png,image/webp" style="display:none">
                        </div>
                        <div class="upload-progress mt-2" id="editUploadProgress" style="display:none">
                            <p class="fw-semibold text-muted small mb-1">Đang tải lên...</p>
                            <div class="progress" style="height:4px"><div class="progress-bar bg-danger" id="editUploadBar" style="width:0;transition:width .3s"></div></div>
                        </div>
                    </div>
                </div>

                <div class="preview-grid" id="editImageGrid">
                    <div class="text-center w-100 py-3 text-muted"><span class="spinner-border spinner-border-sm"></span> Đang tải ảnh...</div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" name="edit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i>Cập Nhật
                </button>
            </div>
        </form>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer" style="z-index:9999"></div>

<script>
const UPLOAD_URL = '<?= url('admin/upload') ?>';
const GET_IMAGES_URL = '<?= url('admin/get_images') ?>';
let pendingFiles = [];
let currentEditId = 0;

function showToast(msg, type='success') {
    const id = 'toast_'+Date.now(), color = type==='success'?'#16a34a':'#dc2626', icon = type==='success'?'fa-check-circle':'fa-exclamation-circle';
    document.getElementById('toastContainer').insertAdjacentHTML('beforeend',
        `<div id="${id}" style="background:#fff;border-left:4px solid ${color};padding:14px 18px;border-radius:10px;margin-top:10px;box-shadow:0 4px 20px rgba(0,0,0,.12);display:flex;align-items:center;gap:10px;animation:fadeInUp .3s ease">
            <i class="fas ${icon}" style="color:${color};font-size:1.1rem"></i>
            <span style="font-size:.9rem;color:#1e293b">${msg}</span></div>`);
    setTimeout(() => document.getElementById(id)?.remove(), 3500);
}

function editMatHang(id, name, giagoc, giaban, soluongton, catId, mota) {
    currentEditId = id;
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_tenmathang').value = name;
    document.getElementById('edit_giagoc').value = giagoc;
    document.getElementById('edit_giaban').value = giaban;
    document.getElementById('edit_soluongton').value = soluongton;
    document.getElementById('edit_danhmucchitiet_id').value = catId;
    document.getElementById('edit_mota').value = mota;
    loadProductImages(id);
}

async function loadProductImages(id) {
    const grid = document.getElementById('editImageGrid');
    grid.innerHTML = '<div class="text-center w-100 py-3 text-muted"><span class="spinner-border spinner-border-sm"></span> Đang tải ảnh...</div>';
    try {
        const res = await fetch(`${GET_IMAGES_URL}?id=${id}`).then(r=>r.json());
        if(res.success) {
            grid.innerHTML = res.images.length ? '' : '<div class="text-center w-100 py-3 text-muted small">Chưa có ảnh nào.</div>';
            res.images.forEach(img => renderImage(img, grid));
        }
    } catch(e) {
        grid.innerHTML = '<div class="text-danger w-100 py-3 text-center small">Lỗi tải ảnh.</div>';
    }
}

function renderImage(img, container) {
    const item = document.createElement('div');
    item.className = 'preview-item' + (img.la_anh_chinh==1 ? ' main-badge' : '');
    item.id = 'editImg_' + img.id;
    const fullPath = img.duongdan.startsWith('http') ? img.duongdan : (BASE_URL + '/' + img.duongdan).replace(/\/+/g, '/');
    
    let actions = `<button type="button" class="remove-btn" onclick="deleteImg(${img.id}, ${img.la_anh_chinh})" title="Xóa"><i class="fas fa-times"></i></button>`;
    if (img.la_anh_chinh == 0) {
        actions += `<button type="button" class="remove-btn" style="right:28px;background:rgba(234,179,8,.85)" onclick="setMainImg(${img.id})" title="Đặt làm ảnh chính"><i class="fas fa-star"></i></button>`;
    }
    
    item.innerHTML = `<img src="${fullPath}" alt="">` + actions;
    container.appendChild(item);
}

async function setMainImg(id) {
    const fd = new FormData(); fd.append('action', 'set_main'); fd.append('hinhanh_id', id); fd.append('mathang_id', currentEditId);
    const res = await fetch(UPLOAD_URL, {method:'POST', body:fd}).then(r=>r.json());
    if (res.success) {
        showToast('Đã đặt làm ảnh chính');
        loadProductImages(currentEditId);
    } else showToast(res.message||'Lỗi', 'error');
}

async function deleteImg(id, isMain) {
    const msg = isMain ? 'Đây là ảnh chính. Bạn có chắc muốn xóa?' : 'Bạn có chắc muốn xóa ảnh này?';
    showConfirmModal(msg, async () => {
        const fd = new FormData(); fd.append('action', 'delete'); fd.append('hinhanh_id', id); fd.append('mathang_id', currentEditId);
        const res = await fetch(UPLOAD_URL, {method:'POST', body:fd}).then(r=>r.json());
        if (res.success) {
            document.getElementById('editImg_'+id)?.remove();
            showToast('Đã xóa ảnh');
        } else showToast(res.message||'Lỗi', 'error');
    });
}

async function uploadExistingImages(files) {
    if(!files.length || currentEditId===0) return;
    const box = document.getElementById('editUploadProgress'), bar = document.getElementById('editUploadBar');
    box.style.display = 'block'; bar.style.width = '0%';
    const fd = new FormData(); fd.append('action', 'upload'); fd.append('mathang_id', currentEditId);
    for(let f of files) fd.append('files[]', f);
    
    try {
        const res = await new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', e => { if(e.lengthComputable) bar.style.width = Math.round(e.loaded/e.total*100)+'%' });
            xhr.onload = () => resolve(JSON.parse(xhr.responseText));
            xhr.onerror = reject;
            xhr.open('POST', UPLOAD_URL); xhr.send(fd);
        });
        if(res.success && res.uploaded) {
            showToast('Tải lên thành công');
            const grid = document.getElementById('editImageGrid');
            if(grid.innerHTML.includes('Chưa có ảnh')) grid.innerHTML = '';
            res.uploaded.forEach(img => renderImage(img, grid));
        } else {
            showToast('Lỗi tải lên', 'error');
        }
    } catch(e) { showToast('Lỗi kết nối', 'error'); }
    setTimeout(() => box.style.display = 'none', 1000);
}

const editDz = document.getElementById('editModalDropZone'), editInput = document.getElementById('editModalFileInput');
editDz.addEventListener('dragover', e => { e.preventDefault(); editDz.classList.add('dragover'); });
editDz.addEventListener('dragleave', () => editDz.classList.remove('dragover'));
editDz.addEventListener('drop', e => { e.preventDefault(); editDz.classList.remove('dragover'); uploadExistingImages(e.dataTransfer.files); });
editInput.addEventListener('change', function() { uploadExistingImages(this.files); this.value=''; });

function addPreviews(files) {
    const grid = document.getElementById('previewGrid');
    const startIdx = pendingFiles.length;
    [...files].forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = e => {
            const item = document.createElement('div');
            item.className = 'preview-item';
            item.dataset.fileIdx = startIdx + idx;
            item.innerHTML = `<img src="${e.target.result}" alt=""><button type="button" class="remove-btn" onclick="removePreview(this)"><i class="fas fa-times"></i></button>`;
            grid.appendChild(item);
            refreshMainBadge();
        };
        reader.readAsDataURL(file);
        pendingFiles.push(file);
    });
}
function removePreview(btn) {
    const item = btn.closest('.preview-item');
    pendingFiles.splice(parseInt(item.dataset.fileIdx), 1);
    item.remove();
    document.querySelectorAll('#previewGrid .preview-item').forEach((el,i) => el.dataset.fileIdx = i);
    refreshMainBadge();
}
function refreshMainBadge() {
    document.querySelectorAll('#previewGrid .preview-item').forEach((el,i) => el.classList.toggle('main-badge', i===0));
}
const dz = document.getElementById('modalDropZone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('dragover'); });
dz.addEventListener('dragleave', () => dz.classList.remove('dragover'));
dz.addEventListener('drop', e => { e.preventDefault(); dz.classList.remove('dragover'); addPreviews(e.dataTransfer.files); });
document.getElementById('modalFileInput').addEventListener('change', function() { addPreviews(this.files); this.value=''; });

document.getElementById('addForm').addEventListener('submit', async function(e) {
    if (!pendingFiles.length) return;
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
    try {
        const fd = new FormData(); fd.append('action','upload_temp');
        pendingFiles.forEach(f => fd.append('files[]', f));
        const res = await fetch(UPLOAD_URL, {method:'POST',body:fd}).then(r=>r.json());
        if (res.temp_paths) document.getElementById('uploadedImagesField').value = JSON.stringify(res.temp_paths);
    } catch(err) {}
    this.submit();
});
document.getElementById('addModal').addEventListener('hidden.bs.modal', () => {
    pendingFiles = []; document.getElementById('previewGrid').innerHTML = '';
    document.getElementById('uploadedImagesField').value = '[]';
    const btn = document.getElementById('submitBtn');
    btn.disabled = false; btn.innerHTML = '<i class="fas fa-save me-2"></i>Lưu Sản Phẩm';
});
</script>
