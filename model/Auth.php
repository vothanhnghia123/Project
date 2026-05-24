<?php
// ============================================================
//  model/Auth.php
//  Xử lý xác thực: login, register, updateProfile, changePassword
// ============================================================

require_once BASE_PATH . '/model/master.php';

class AuthModel extends MasterModel
{
    /**
     * Tìm user theo email + mật khẩu (md5)
     */
    public function login(string $email, string $password): array|false
    {
        $hashed = md5($password);
        $stmt = $this->db->prepare(
            'SELECT * FROM nguoidung
             WHERE Email = :email AND MatKhau = :mk
             LIMIT 1'
        );
        $stmt->bindValue(':email', $email,  PDO::PARAM_STR);
        $stmt->bindValue(':mk',   $hashed,  PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch() ?: false;
    }

    /**
     * Kiểm tra email đã tồn tại chưa
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare(
            'SELECT IDNguoiDung FROM nguoidung WHERE Email = :email LIMIT 1'
        );
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return (bool) $stmt->fetch();
    }

    /**
     * Đăng ký tài khoản mới (IDVaiTro = 2 = user)
     */
    public function register(string $hoTen, string $email, string $password): bool
    {
        $hashed = md5($password);
        $stmt = $this->db->prepare(
            'INSERT INTO nguoidung (HoTen, Email, MatKhau, IDVaiTro)
             VALUES (:ht, :em, :mk, 2)'
        );
        $stmt->bindValue(':ht', $hoTen,  PDO::PARAM_STR);
        $stmt->bindValue(':em', $email,  PDO::PARAM_STR);
        $stmt->bindValue(':mk', $hashed, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Lấy thông tin user theo ID
     */
    public function getUserById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM nguoidung WHERE IDNguoiDung = :id LIMIT 1'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: false;
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile(int $id, string $hoTen, string $email, string $dienThoai, string $diaChi): bool
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

    /**
     * Kiểm tra mật khẩu cũ có đúng không
     */
    public function checkPassword(int $id, string $oldPassword): bool
    {
        $hashed = md5($oldPassword);
        $stmt = $this->db->prepare(
            'SELECT IDNguoiDung FROM nguoidung
             WHERE IDNguoiDung = :id AND MatKhau = :mk LIMIT 1'
        );
        $stmt->bindValue(':id', $id,     PDO::PARAM_INT);
        $stmt->bindValue(':mk', $hashed, PDO::PARAM_STR);
        $stmt->execute();
        return (bool) $stmt->fetch();
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(int $id, string $newPassword): bool
    {
        $hashed = md5($newPassword);
        $stmt = $this->db->prepare(
            'UPDATE nguoidung SET MatKhau=:mk WHERE IDNguoiDung=:id'
        );
        $stmt->bindValue(':mk', $hashed, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id,     PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Lịch sử đơn hàng
     */
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
