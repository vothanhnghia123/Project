<?php
// ============================================================
//  Model\News — Danh mục, Thể loại, Người dùng (profile, orders)
// ============================================================

require_once BASE_PATH . '/model/master.php';

class News extends MasterModel
{
    // ── Danh mục ──────────────────────────────────────────────

    public function getAllDanhmuc(): array
    {
        return $this->db->query('SELECT * FROM danhmuc ORDER BY IDDanhMuc ASC')
                        ->fetchAll();
    }

    public function getDanhmucByName(string $tenDanhmuc): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM danhmuc WHERE TenDanhMuc = :ten LIMIT 1'
        );
        $stmt->bindValue(':ten', $tenDanhmuc, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function getDanhmucById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM danhmuc WHERE IDDanhMuc = :id LIMIT 1'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    // ── Thể loại ──────────────────────────────────────────────

    public function getTheloaiByDanhmuc(int $idDanhmuc): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM theloai WHERE IDDanhMuc = :id ORDER BY IDTheLoai ASC'
        );
        $stmt->bindValue(':id', $idDanhmuc, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllTheloai(): array
    {
        return $this->db->query(
            'SELECT theloai.*, danhmuc.TenDanhMuc
             FROM theloai
             JOIN danhmuc ON theloai.IDDanhMuc = danhmuc.IDDanhMuc
             ORDER BY theloai.IDDanhMuc, theloai.IDTheLoai'
        )->fetchAll();
    }

    public function getTheloaiById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM theloai WHERE IDTheLoai = :id LIMIT 1'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    // ── Người dùng (profile) ──────────────────────────────────

    public function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM nguoidung WHERE IDNguoiDung = :id LIMIT 1'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function updateUserInfo(int $id, string $hoTen, string $email, string $dienThoai, string $diaChi): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE nguoidung
             SET HoTen=:ht, Email=:em, DienThoai=:dt, DiaChi=:dc
             WHERE IDNguoiDung=:id'
        );
        $stmt->bindValue(':ht', $hoTen,     PDO::PARAM_STR);
        $stmt->bindValue(':em', $email,     PDO::PARAM_STR);
        $stmt->bindValue(':dt', $dienThoai, PDO::PARAM_STR);
        $stmt->bindValue(':dc', $diaChi,    PDO::PARAM_STR);
        $stmt->bindValue(':id', $id,        PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function changePassword(int $id, string $newHashedPass): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE nguoidung SET MatKhau=:mk WHERE IDNguoiDung=:id'
        );
        $stmt->bindValue(':mk', $newHashedPass, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id,            PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getOrdersByUser(int $idNguoiDung): array
    {
        $stmt = $this->db->prepare(
            'SELECT donhang.*,
                    SUM(chitietdonhang.SoLuong) AS TongSoLuong
             FROM donhang
             JOIN chitietdonhang ON donhang.IDDonHang = chitietdonhang.IDDonHang
             WHERE donhang.IDNguoiDung = :id
             GROUP BY donhang.IDDonHang
             ORDER BY donhang.IDDonHang DESC'
        );
        $stmt->bindValue(':id', $idNguoiDung, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
