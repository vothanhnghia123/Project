<?php
class ModelUser extends MasterModel {

    // ── Đăng nhập ─────────────────────────────────────────────

    public function login($email, $password) {
        $hashed = md5($password);
        $em     = $GLOBALS["connect"]->db->quote($email);
        $mk     = $GLOBALS["connect"]->db->quote($hashed);
        $sql    = "SELECT * FROM nguoidung WHERE Email = $em AND MatKhau = $mk LIMIT 1";
        $row    = $GLOBALS["connect"]->getInstance($sql);
        return $row ?: false;
    }

    // ── Đăng ký ───────────────────────────────────────────────

    public function emailExists($email) {
        $em  = $GLOBALS["connect"]->db->quote($email);
        $sql = "SELECT IDNguoiDung FROM nguoidung WHERE Email = $em LIMIT 1";
        $row = $GLOBALS["connect"]->getInstance($sql);
        return (bool)$row;
    }

    public function register($hoTen, $email, $password) {
        $hashed = md5($password);
        $ht  = $GLOBALS["connect"]->db->quote($hoTen);
        $em  = $GLOBALS["connect"]->db->quote($email);
        $mk  = $GLOBALS["connect"]->db->quote($hashed);
        $sql = "INSERT INTO nguoidung (HoTen, Email, MatKhau, IDVaiTro) VALUES ($ht, $em, $mk, 2)";
        return $GLOBALS["connect"]->exec($sql);
    }

    // ── Thông tin người dùng ──────────────────────────────────

    public function getUserById($id) {
        $id  = (int)$id;
        $sql = "SELECT * FROM nguoidung WHERE IDNguoiDung = $id LIMIT 1";
        $row = $GLOBALS["connect"]->getInstance($sql);
        return $row ?: false;
    }

    public function updateProfile($id, $hoTen, $email, $dienThoai, $diaChi) {
        $id = (int)$id;
        $ht = $GLOBALS["connect"]->db->quote($hoTen);
        $em = $GLOBALS["connect"]->db->quote($email);
        $dt = $GLOBALS["connect"]->db->quote($dienThoai);
        $dc = $GLOBALS["connect"]->db->quote($diaChi);
        $sql = "UPDATE nguoidung SET HoTen=$ht, Email=$em, DienThoai=$dt, DiaChi=$dc
                WHERE IDNguoiDung = $id";
        return $GLOBALS["connect"]->exec($sql);
    }

    // ── Mật khẩu ──────────────────────────────────────────────

    public function checkPassword($id, $oldPassword) {
        $id     = (int)$id;
        $hashed = $GLOBALS["connect"]->db->quote(md5($oldPassword));
        $sql    = "SELECT IDNguoiDung FROM nguoidung WHERE IDNguoiDung = $id AND MatKhau = $hashed LIMIT 1";
        $row    = $GLOBALS["connect"]->getInstance($sql);
        return (bool)$row;
    }

    public function changePassword($id, $newPassword) {
        $id  = (int)$id;
        $mk  = $GLOBALS["connect"]->db->quote(md5($newPassword));
        $sql = "UPDATE nguoidung SET MatKhau = $mk WHERE IDNguoiDung = $id";
        return $GLOBALS["connect"]->exec($sql);
    }

    // ── Lịch sử đơn hàng ──────────────────────────────────────

    public function getOrdersByUser($idNguoiDung) {
        $id  = (int)$idNguoiDung;
        $sql = "SELECT donhang.*, SUM(chitietdonhang.SoLuong) AS TongSoLuong
                FROM donhang
                JOIN chitietdonhang ON donhang.IDDonHang = chitietdonhang.IDDonHang
                WHERE donhang.IDNguoiDung = $id
                GROUP BY donhang.IDDonHang
                ORDER BY donhang.IDDonHang DESC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }
}
