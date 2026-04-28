<?php
class HinhAnhModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getByMatHang(int $mathangId): array {
        $s = $this->db->prepare(
            "SELECT * FROM hinhanh_mathang WHERE mathang_id = ? ORDER BY thu_tu ASC, id ASC"
        );
        $s->execute([$mathangId]);
        return $s->fetchAll();
    }

    public function countByMatHang(int $mathangId): int {
        $s = $this->db->prepare("SELECT COUNT(*) FROM hinhanh_mathang WHERE mathang_id = ?");
        $s->execute([$mathangId]);
        return (int)$s->fetchColumn();
    }

    public function getById(int $id): array|false {
        $s = $this->db->prepare("SELECT * FROM hinhanh_mathang WHERE id = ?");
        $s->execute([$id]);
        return $s->fetch();
    }

    public function create(int $mathangId, string $path, int $isMain, int $order): int {
        $s = $this->db->prepare(
            "INSERT INTO hinhanh_mathang (mathang_id, duongdan, la_anh_chinh, thu_tu) VALUES (?,?,?,?)"
        );
        $s->execute([$mathangId, $path, $isMain, $order]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM hinhanh_mathang WHERE id = ?")->execute([$id]);
    }

    public function deleteByMatHang(int $mathangId): void {
        $this->db->prepare("DELETE FROM hinhanh_mathang WHERE mathang_id = ?")->execute([$mathangId]);
    }

    public function setMain(int $id, int $mathangId): void {
        $this->db->prepare("UPDATE hinhanh_mathang SET la_anh_chinh = 0 WHERE mathang_id = ?")->execute([$mathangId]);
        $this->db->prepare("UPDATE hinhanh_mathang SET la_anh_chinh = 1 WHERE id = ?")->execute([$id]);
    }

    public function syncMainToProduct(int $mathangId): void {
        $s = $this->db->prepare(
            "SELECT duongdan FROM hinhanh_mathang WHERE mathang_id = ? AND la_anh_chinh = 1 LIMIT 1"
        );
        $s->execute([$mathangId]);
        $img = $s->fetchColumn();
        $this->db->prepare("UPDATE mathang SET hinhanh = ? WHERE id = ?")->execute([$img ?: null, $mathangId]);
    }

    public function linkTempImages(int $mathangId, array $paths): void {
        $existing = $this->countByMatHang($mathangId);
        foreach ($paths as $i => $path) {
            $isMain = ($existing === 0 && $i === 0) ? 1 : 0;
            $this->create($mathangId, $path, $isMain, $existing + $i);
        }
        $this->syncMainToProduct($mathangId);
    }
}
