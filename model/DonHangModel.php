<?php
class DonHangModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->initTables();
    }

    private function initTables(): void {
        // Auto-create tables if they don't exist
        try {
            $this->db->query("SELECT 1 FROM donhang LIMIT 1");
        } catch (Exception $e) {
            $this->db->exec("
                CREATE TABLE `donhang` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `nguoidung_id` int(11) DEFAULT NULL,
                  `hoten` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
                  `sodienthoai` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `diachi` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `ghichu` text COLLATE utf8mb4_unicode_ci,
                  `tongtien` float NOT NULL DEFAULT 0,
                  `phuongthucthanhtoan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                  `trangthai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chờ xử lý',
                  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp(),
                  PRIMARY KEY (`id`),
                  FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
            $this->db->exec("
                CREATE TABLE `chitietdonhang` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `donhang_id` int(11) NOT NULL,
                  `mathang_id` int(11) NOT NULL,
                  `soluong` int(11) NOT NULL,
                  `dongia` float NOT NULL,
                  PRIMARY KEY (`id`),
                  FOREIGN KEY (`donhang_id`) REFERENCES `donhang` (`id`) ON DELETE CASCADE,
                  FOREIGN KEY (`mathang_id`) REFERENCES `mathang` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }
    }

    public function createOrder(array $orderData, array $items): int {
        try {
            $this->db->beginTransaction();

            // Insert into donhang
            $s = $this->db->prepare(
                "INSERT INTO donhang (nguoidung_id, hoten, sodienthoai, email, diachi, ghichu, tongtien, phuongthucthanhtoan) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $s->execute([
                $orderData['nguoidung_id'],
                $orderData['hoten'],
                $orderData['sodienthoai'],
                $orderData['email'],
                $orderData['diachi'],
                $orderData['ghichu'],
                $orderData['tongtien'],
                $orderData['phuongthucthanhtoan']
            ]);
            
            $orderId = (int)$this->db->lastInsertId();

            // Insert into chitietdonhang
            $sDetail = $this->db->prepare(
                "INSERT INTO chitietdonhang (donhang_id, mathang_id, soluong, dongia) VALUES (?, ?, ?, ?)"
            );
            
            // Deduct stock
            $sStock = $this->db->prepare(
                "UPDATE mathang SET soluongton = soluongton - ?, luotmua = luotmua + ? WHERE id = ? AND soluongton >= ?"
            );

            foreach ($items as $item) {
                $qty = (int)$item['qty'];
                $id = (int)$item['id'];
                $price = (float)$item['price'];

                if ($qty > 0) {
                    $sDetail->execute([$orderId, $id, $qty, $price]);
                    $sStock->execute([$qty, $qty, $id, $qty]);
                }
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM donhang ORDER BY ngaytao DESC")->fetchAll();
    }

    public function getByUserId(int $userId): array {
        $s = $this->db->prepare("SELECT * FROM donhang WHERE nguoidung_id = ? ORDER BY ngaytao DESC");
        $s->execute([$userId]);
        return $s->fetchAll();
    }

    public function getById(int $id): array|false {
        $s = $this->db->prepare("SELECT * FROM donhang WHERE id = ?");
        $s->execute([$id]);
        return $s->fetch();
    }

    public function getDetails(int $orderId): array {
        $s = $this->db->prepare(
            "SELECT c.*, m.tenmathang, m.hinhanh 
             FROM chitietdonhang c 
             JOIN mathang m ON c.mathang_id = m.id 
             WHERE c.donhang_id = ?"
        );
        $s->execute([$orderId]);
        return $s->fetchAll();
    }
}
