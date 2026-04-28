<?php
class NguoiDungModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function findByEmail(string $email): array|false {
        $s = $this->db->prepare("SELECT * FROM nguoidung WHERE email = ?");
        $s->execute([$email]);
        return $s->fetch();
    }

    public function emailExists(string $email): bool {
        $s = $this->db->prepare("SELECT id FROM nguoidung WHERE email = ?");
        $s->execute([$email]);
        return (bool)$s->fetch();
    }

    public function create(array $data): bool {
        $s = $this->db->prepare(
            "INSERT INTO nguoidung (email, matkhau, hoten, sodienthoai, diachi, phanquyen) VALUES (?,?,?,?,?,'khachhang')"
        );
        return $s->execute([$data['email'], $data['matkhau'], $data['hoten'], $data['sodienthoai'], $data['diachi']]);
    }
    public function getById(int $id): array|false {
        $s = $this->db->prepare("SELECT * FROM nguoidung WHERE id = ?");
        $s->execute([$id]);
        return $s->fetch();
    }
}
