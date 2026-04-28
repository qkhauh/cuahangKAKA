<?php
class MatHangModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getLatest(int $limit = 8): array {
        return $this->db->query(
            "SELECT m.*, d.tendanhmuc as tendanhmuc_chitiet
             FROM mathang m JOIN danhmucchitiet d ON m.danhmucchitiet_id = d.id
             ORDER BY m.id DESC LIMIT $limit"
        )->fetchAll();
    }

    public function getFiltered(?string $categoryId, ?string $query): array {
        $where = []; $params = [];
        if ($categoryId !== null && $categoryId !== '') {
            $where[] = "m.danhmucchitiet_id = ?"; $params[] = $categoryId;
        }
        if ($query && trim($query) !== '') {
            $where[] = "m.tenmathang LIKE ?"; $params[] = '%' . trim($query) . '%';
        }
        $sql = "SELECT m.*, d.tendanhmuc as tendanhmuc_chitiet,
                (SELECT COUNT(*) FROM hinhanh_mathang WHERE mathang_id = m.id) as so_anh
                FROM mathang m JOIN danhmucchitiet d ON m.danhmucchitiet_id = d.id"
             . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
             . " ORDER BY m.id DESC";
        $s = $this->db->prepare($sql);
        $s->execute($params);
        return $s->fetchAll();
    }

    public function getAll(): array {
        return $this->db->query(
            "SELECT m.*, d.tendanhmuc as tendanhmuc_chitiet,
             (SELECT COUNT(*) FROM hinhanh_mathang WHERE mathang_id = m.id) as so_anh
             FROM mathang m JOIN danhmucchitiet d ON m.danhmucchitiet_id = d.id
             ORDER BY m.id DESC"
        )->fetchAll();
    }

    public function getById(int $id): array|false {
        $s = $this->db->prepare(
            "SELECT m.*, d.tendanhmuc as tendanhmuc_chitiet, d.danhmuc_id,
             p.tendanhmuc as tendanhmuc_cha
             FROM mathang m 
             JOIN danhmucchitiet d ON m.danhmucchitiet_id = d.id
             JOIN danhmuc p ON d.danhmuc_id = p.id
             WHERE m.id = ?"
        );
        $s->execute([$id]);
        return $s->fetch();
    }

    public function getRelated(int $danhMucChiTietId, int $excludeId, int $limit = 6): array {
        $s = $this->db->prepare(
            "SELECT m.*, d.tendanhmuc as tendanhmuc_chitiet
             FROM mathang m JOIN danhmucchitiet d ON m.danhmucchitiet_id = d.id
             WHERE m.danhmucchitiet_id = ? AND m.id != ?
             ORDER BY m.luotxem DESC LIMIT $limit"
        );
        $s->execute([$danhMucChiTietId, $excludeId]);
        return $s->fetchAll();
    }

    public function create(array $data): int {
        $s = $this->db->prepare(
            "INSERT INTO mathang (tenmathang, mota, giagoc, giaban, soluongton, danhmucchitiet_id) VALUES (?,?,?,?,?,?)"
        );
        $s->execute([$data['tenmathang'], $data['mota'], $data['giagoc'], $data['giaban'], $data['soluongton'], $data['danhmucchitiet_id']]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $s = $this->db->prepare(
            "UPDATE mathang SET tenmathang = ?, mota = ?, giagoc = ?, giaban = ?, soluongton = ?, danhmucchitiet_id = ? WHERE id = ?"
        );
        $s->execute([$data['tenmathang'], $data['mota'], $data['giagoc'], $data['giaban'], $data['soluongton'], $data['danhmucchitiet_id'], $id]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM mathang WHERE id = ?")->execute([$id]);
    }

    public function countByDanhMucChiTiet(int $danhMucChiTietId): int {
        $s = $this->db->prepare("SELECT COUNT(*) FROM mathang WHERE danhmucchitiet_id = ?");
        $s->execute([$danhMucChiTietId]);
        return (int)$s->fetchColumn();
    }

    public function getDashboardCounts(): array {
        return [
            'danhmuc'   => $this->db->query("SELECT COUNT(*) FROM danhmuc")->fetchColumn(),
            'chitiet'   => $this->db->query("SELECT COUNT(*) FROM danhmucchitiet")->fetchColumn(),
            'mathang'   => $this->db->query("SELECT COUNT(*) FROM mathang")->fetchColumn(),
        ];
    }

    public function deductStock(array $items): void {
        $s = $this->db->prepare("UPDATE mathang SET soluongton = soluongton - ?, luotmua = luotmua + ? WHERE id = ? AND soluongton >= ?");
        foreach ($items as $item) {
            $qty = (int)$item['qty'];
            $id = (int)$item['id'];
            if ($qty > 0) {
                $s->execute([$qty, $qty, $id, $qty]);
            }
        }
    }
}
