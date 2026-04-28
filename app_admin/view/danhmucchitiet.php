<?php $activeAdmin = 'danhmucchitiet'; ?>
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Quản Lý Danh Mục Sản Phẩm</h2>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-2"></i>Thêm Mới
        </button>
    </div>

    <?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>ID</th><th>Tên Danh Mục</th><th>Thuộc Danh Mục</th><th>Số Mặt Hàng</th><th class="text-end">Hành Động</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= htmlspecialchars($item['tendanhmuc']) ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($item['tendanhmuc_cha']) ?></span></td>
                        <td>
                            <?php if ($item['so_mathang'] > 0): ?>
                                <span class="badge bg-success"><?= $item['so_mathang'] ?> sản phẩm</span>
                            <?php else: ?>
                                <span class="badge bg-light text-dark border">0 sản phẩm</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editDanhMucChiTiet(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['tendanhmuc'])) ?>', <?= $item['danhmuc_id'] ?>)" data-bs-toggle="modal" data-bs-target="#editModal" title="Sửa"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="<?= url('admin/danhmucchitiet') ?>" class="d-inline"
                                  onsubmit="return confirmFormSubmit(this, 'Không thể hoàn tác! Bạn có chắc chắn muốn xóa danh mục sản phẩm này?')">
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

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="<?= url('admin/danhmucchitiet') ?>" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Thêm Danh Mục Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên Danh Mục Sản Phẩm</label>
                    <input type="text" name="tendanhmuc" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Thuộc Danh Mục</label>
                    <select name="danhmuc_id" class="form-select" required>
                        <?php foreach ($parent_cats as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['tendanhmuc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" name="add" class="btn btn-danger">Lưu lại</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="<?= url('admin/danhmucchitiet') ?>" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Sửa Danh Mục Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-3">
                    <label class="form-label">Tên Danh Mục Sản Phẩm</label>
                    <input type="text" name="tendanhmuc" id="edit_tendanhmuc" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Thuộc Danh Mục</label>
                    <select name="danhmuc_id" id="edit_danhmuc_id" class="form-select" required>
                        <?php foreach ($parent_cats as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['tendanhmuc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" name="edit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
function editDanhMucChiTiet(id, name, parentId) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_tendanhmuc').value = name;
    document.getElementById('edit_danhmuc_id').value = parentId;
}
</script>
