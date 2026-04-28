<?php
class DanhMucModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM danhmuc ORDER BY id DESC")->fetchAll();
    }

    public function getWithDetails(): array {
        $cats = $this->db->query("SELECT * FROM danhmuc")->fetchAll();
        foreach ($cats as &$cat) {
            $s = $this->db->prepare("SELECT * FROM danhmucchitiet WHERE danhmuc_id = ?");
            $s->execute([$cat['id']]);
            $cat['details'] = $s->fetchAll();
        }
        return $cats;
    }

    public function create(string $name): void {
        $this->db->prepare("INSERT INTO danhmuc (tendanhmuc) VALUES (?)")->execute([$name]);
    }

    public function update(int $id, string $name): void {
        $this->db->prepare("UPDATE danhmuc SET tendanhmuc = ? WHERE id = ?")->execute([$name, $id]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM danhmuc WHERE id = ?")->execute([$id]);
    }
}
