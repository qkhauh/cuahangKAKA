<?php
class DanhMucChiTietModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(): array {
        return $this->db->query(
            "SELECT d.*, c.tendanhmuc as tendanhmuc_cha, 
             (SELECT COUNT(*) FROM mathang WHERE danhmucchitiet_id = d.id) as so_mathang
             FROM danhmucchitiet d
             JOIN danhmuc c ON d.danhmuc_id = c.id ORDER BY d.id DESC"
        )->fetchAll();
    }

    public function getAllParents(): array {
        return $this->db->query("SELECT * FROM danhmuc")->fetchAll();
    }

    public function create(string $name, int $danhMucId): void {
        $this->db->prepare("INSERT INTO danhmucchitiet (tendanhmuc, danhmuc_id) VALUES (?,?)")
                 ->execute([$name, $danhMucId]);
    }

    public function update(int $id, string $name, int $danhMucId): void {
        $this->db->prepare("UPDATE danhmucchitiet SET tendanhmuc = ?, danhmuc_id = ? WHERE id = ?")
                 ->execute([$name, $danhMucId, $id]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM danhmucchitiet WHERE id = ?")->execute([$id]);
    }

    public function countByDanhMuc(int $danhMucId): int {
        $s = $this->db->prepare("SELECT COUNT(*) FROM danhmucchitiet WHERE danhmuc_id = ?");
        $s->execute([$danhMucId]);
        return (int)$s->fetchColumn();
    }

    // For product form dropdown
    public function getAllWithParent(): array {
        return $this->db->query(
            "SELECT d.*, c.tendanhmuc as tendanhmuc_cha FROM danhmucchitiet d
             JOIN danhmuc c ON d.danhmuc_id = c.id"
        )->fetchAll();
    }
}
