<?php
class ModelNews extends MasterModel {

    // ── Danh mục ──────────────────────────────────────────────

    public function getAllDanhmuc() {
        $sql = "SELECT * FROM danhmuc ORDER BY IDDanhMuc ASC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function getDanhmucById($id) {
        $id  = (int)$id;
        $sql = "SELECT * FROM danhmuc WHERE IDDanhMuc = $id LIMIT 1";
        $row = $GLOBALS["connect"]->getInstance($sql);
        return $row ?: null;
    }

    public function getDanhmucByName($tenDanhmuc) {
        $ten = $GLOBALS["connect"]->db->quote($tenDanhmuc);
        $sql = "SELECT * FROM danhmuc WHERE TenDanhMuc = $ten LIMIT 1";
        $row = $GLOBALS["connect"]->getInstance($sql);
        return $row ?: null;
    }

    // ── Thể loại ──────────────────────────────────────────────

    public function getAllTheloai() {
        $sql = "SELECT theloai.*, danhmuc.TenDanhMuc
                FROM theloai
                JOIN danhmuc ON theloai.IDDanhMuc = danhmuc.IDDanhMuc
                ORDER BY theloai.IDDanhMuc, theloai.IDTheLoai";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function getTheloaiById($id) {
        $id  = (int)$id;
        $sql = "SELECT * FROM theloai WHERE IDTheLoai = $id LIMIT 1";
        $row = $GLOBALS["connect"]->getInstance($sql);
        return $row ?: null;
    }

    public function getTheloaiByDanhmuc($idDanhmuc) {
        $id  = (int)$idDanhmuc;
        $sql = "SELECT * FROM theloai WHERE IDDanhMuc = $id ORDER BY IDTheLoai ASC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    // ── Người dùng ────────────────────────────────────────────

    public function getUserById($id) {
        $id  = (int)$id;
        $sql = "SELECT * FROM nguoidung WHERE IDNguoiDung = $id LIMIT 1";
        $row = $GLOBALS["connect"]->getInstance($sql);
        return $row ?: null;
    }

    public function updateUserInfo($id, $hoTen, $email, $dienThoai, $diaChi) {
        $id  = (int)$id;
        $ht  = $GLOBALS["connect"]->db->quote($hoTen);
        $em  = $GLOBALS["connect"]->db->quote($email);
        $dt  = $GLOBALS["connect"]->db->quote($dienThoai);
        $dc  = $GLOBALS["connect"]->db->quote($diaChi);
        $sql = "UPDATE nguoidung SET HoTen=$ht, Email=$em, DienThoai=$dt, DiaChi=$dc
                WHERE IDNguoiDung = $id";
        return $GLOBALS["connect"]->exec($sql);
    }

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
