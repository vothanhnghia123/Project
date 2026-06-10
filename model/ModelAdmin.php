<?php
class ModelAdmin extends MasterModel {

    // ── Dashboard ──────────────────────────────────────────────

    public function getDoanhThuHomNay() {
        $sql = "SELECT COALESCE(SUM(TongTien),0) FROM donhang WHERE DATE(NgayDat) = CURDATE()";
        return (float)$GLOBALS["connect"]->getList($sql)->fetchColumn();
    }

    public function getDoanhThuThangNay() {
        $sql = "SELECT COALESCE(SUM(TongTien),0) FROM donhang
                WHERE MONTH(NgayDat)=MONTH(CURDATE()) AND YEAR(NgayDat)=YEAR(CURDATE())";
        return (float)$GLOBALS["connect"]->getList($sql)->fetchColumn();
    }

    public function getSoDonChoXacNhan() {
        $sql = "SELECT COUNT(*) FROM donhang WHERE TrangThai = 0";
        return (int)$GLOBALS["connect"]->getList($sql)->fetchColumn();
    }

    public function getTongSach() {
        return (int)$GLOBALS["connect"]->getList("SELECT COUNT(*) FROM sach")->fetchColumn();
    }

    public function getTongKhachHang() {
        return (int)$GLOBALS["connect"]->getList("SELECT COUNT(*) FROM nguoidung WHERE IDVaiTro != 1")->fetchColumn();
    }

    public function getTongDonHang() {
        return (int)$GLOBALS["connect"]->getList("SELECT COUNT(*) FROM donhang")->fetchColumn();
    }

    public function getDoanhThu7Ngay() {
        $sql = "SELECT DATE(NgayDat) AS ngay, COALESCE(SUM(TongTien),0) AS tong
                FROM donhang
                WHERE NgayDat >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                GROUP BY DATE(NgayDat) ORDER BY ngay ASC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function getDonHangMoiNhat($limit = 5) {
        $lim = (int)$limit;
        $sql = "SELECT donhang.*, nguoidung.HoTen
                FROM donhang
                JOIN nguoidung ON donhang.IDNguoiDung = nguoidung.IDNguoiDung
                ORDER BY donhang.IDDonHang DESC LIMIT $lim";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    // ── Sách ──────────────────────────────────────────────────

    public function getAllSach() {
        $sql = "SELECT sach.*, theloai.TenTheLoai, nhaxuatban.TenNXB, tacgia.TenTacGia
                FROM sach
                LEFT JOIN theloai    ON sach.IDTheLoai = theloai.IDTheLoai
                LEFT JOIN nhaxuatban ON sach.IDNXB     = nhaxuatban.IDNXB
                LEFT JOIN tacgia     ON sach.IDTacGia  = tacgia.IDTacGia
                ORDER BY sach.IDSach DESC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function getSachById($id) {
        $id  = (int)$id;
        $sql = "SELECT * FROM sach WHERE IDSach = $id LIMIT 1";
        $row = $GLOBALS["connect"]->getInstance($sql);
        return $row ?: null;
    }

    public function insertSach($d, $hinhanh) {
        $db  = $GLOBALS["connect"]->db;
        $ts  = $db->quote($d['tensach']);
        $tl  = (int)$d['idtheloai'];
        $nxb = (int)$d['idnxb'];
        $tg  = (int)$d['idtacgia'];
        $gia = $db->quote($d['giaban']);
        $sl  = (int)$d['soluong'];
        $mt  = $db->quote($d['mota']);
        $ha  = $db->quote($hinhanh);
        $stp = (int)$d['sotrang'];
        $nxb_nam = $db->quote($d['namxb']);
        $sql = "INSERT INTO sach(TenSach,IDTheLoai,IDNXB,IDTacGia,GiaBan,SoLuong,MoTa,HinhAnh,SoTrang,NamXB)
                VALUES($ts,$tl,$nxb,$tg,$gia,$sl,$mt,$ha,$stp,$nxb_nam)";
        return $GLOBALS["connect"]->exec($sql);
    }

    public function updateSach($id, $d, $hinhanh = null) {
        $id  = (int)$id;
        $db  = $GLOBALS["connect"]->db;
        $ts  = $db->quote($d['tensach']);
        $tl  = (int)$d['idtheloai'];
        $nxb = (int)$d['idnxb'];
        $tg  = (int)$d['idtacgia'];
        $gia = $db->quote($d['giaban']);
        $sl  = (int)$d['soluong'];
        $mt  = $db->quote($d['mota']);
        $stp = (int)$d['sotrang'];
        $nam = $db->quote($d['namxb']);
        if ($hinhanh !== null) {
            $ha  = $db->quote($hinhanh);
            $sql = "UPDATE sach SET TenSach=$ts,IDTheLoai=$tl,IDNXB=$nxb,IDTacGia=$tg,
                    GiaBan=$gia,SoLuong=$sl,MoTa=$mt,HinhAnh=$ha,SoTrang=$stp,NamXB=$nam
                    WHERE IDSach=$id";
        } else {
            $sql = "UPDATE sach SET TenSach=$ts,IDTheLoai=$tl,IDNXB=$nxb,IDTacGia=$tg,
                    GiaBan=$gia,SoLuong=$sl,MoTa=$mt,SoTrang=$stp,NamXB=$nam
                    WHERE IDSach=$id";
        }
        return $GLOBALS["connect"]->exec($sql);
    }

    public function deleteSach($id) {
        $id  = (int)$id;
        $row = $GLOBALS["connect"]->getInstance("SELECT HinhAnh FROM sach WHERE IDSach=$id LIMIT 1");
        $hinhanh = $row ? $row['HinhAnh'] : null;
        $GLOBALS["connect"]->exec("DELETE FROM sach WHERE IDSach=$id");
        return $hinhanh;
    }

    // ── Danh mục ──────────────────────────────────────────────

    public function getAllDanhmuc() {
        return $GLOBALS["connect"]->getList("SELECT * FROM danhmuc ORDER BY IDDanhMuc ASC")->fetchAll();
    }

    public function getDanhmucById($id) {
        $id  = (int)$id;
        $row = $GLOBALS["connect"]->getInstance("SELECT * FROM danhmuc WHERE IDDanhMuc=$id LIMIT 1");
        return $row ?: null;
    }

    public function insertDanhmuc($ten) {
        $ten = $GLOBALS["connect"]->db->quote($ten);
        return $GLOBALS["connect"]->exec("INSERT INTO danhmuc(TenDanhMuc) VALUES($ten)");
    }

    public function updateDanhmuc($id, $ten) {
        $id  = (int)$id;
        $ten = $GLOBALS["connect"]->db->quote($ten);
        return $GLOBALS["connect"]->exec("UPDATE danhmuc SET TenDanhMuc=$ten WHERE IDDanhMuc=$id");
    }

    public function deleteDanhmuc($id) {
        $id = (int)$id;
        return $GLOBALS["connect"]->exec("DELETE FROM danhmuc WHERE IDDanhMuc=$id");
    }

    // ── Thể loại ──────────────────────────────────────────────

    public function getAllTheloai() {
        $sql = "SELECT theloai.*, danhmuc.TenDanhMuc
                FROM theloai JOIN danhmuc ON theloai.IDDanhMuc=danhmuc.IDDanhMuc
                ORDER BY theloai.IDDanhMuc, theloai.IDTheLoai";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function getTheloaiById($id) {
        $id  = (int)$id;
        $row = $GLOBALS["connect"]->getInstance("SELECT * FROM theloai WHERE IDTheLoai=$id LIMIT 1");
        return $row ?: null;
    }

    public function insertTheloai($ten, $idDm) {
        $ten  = $GLOBALS["connect"]->db->quote($ten);
        $idDm = (int)$idDm;
        return $GLOBALS["connect"]->exec("INSERT INTO theloai(TenTheLoai,IDDanhMuc) VALUES($ten,$idDm)");
    }

    public function updateTheloai($id, $ten, $idDm) {
        $id   = (int)$id;
        $ten  = $GLOBALS["connect"]->db->quote($ten);
        $idDm = (int)$idDm;
        return $GLOBALS["connect"]->exec("UPDATE theloai SET TenTheLoai=$ten,IDDanhMuc=$idDm WHERE IDTheLoai=$id");
    }

    public function deleteTheloai($id) {
        $id = (int)$id;
        return $GLOBALS["connect"]->exec("DELETE FROM theloai WHERE IDTheLoai=$id");
    }

    // ── Tác giả ───────────────────────────────────────────────

    public function getAllTacgia() {
        return $GLOBALS["connect"]->getList("SELECT * FROM tacgia ORDER BY IDTacGia DESC")->fetchAll();
    }

    public function getTacgiaById($id) {
        $id  = (int)$id;
        $row = $GLOBALS["connect"]->getInstance("SELECT * FROM tacgia WHERE IDTacGia=$id LIMIT 1");
        return $row ?: null;
    }

    public function insertTacgia($ten, $tieusu) {
        $ten    = $GLOBALS["connect"]->db->quote($ten);
        $tieusu = $GLOBALS["connect"]->db->quote($tieusu);
        return $GLOBALS["connect"]->exec("INSERT INTO tacgia(TenTacGia,TieuSu) VALUES($ten,$tieusu)");
    }

    public function updateTacgia($id, $ten, $tieusu) {
        $id     = (int)$id;
        $ten    = $GLOBALS["connect"]->db->quote($ten);
        $tieusu = $GLOBALS["connect"]->db->quote($tieusu);
        return $GLOBALS["connect"]->exec("UPDATE tacgia SET TenTacGia=$ten,TieuSu=$tieusu WHERE IDTacGia=$id");
    }

    public function deleteTacgia($id) {
        $id = (int)$id;
        return $GLOBALS["connect"]->exec("DELETE FROM tacgia WHERE IDTacGia=$id");
    }

    // ── Nhà xuất bản ──────────────────────────────────────────

    public function getAllNXB() {
        return $GLOBALS["connect"]->getList("SELECT * FROM nhaxuatban ORDER BY IDNXB ASC")->fetchAll();
    }

    public function getNXBById($id) {
        $id  = (int)$id;
        $row = $GLOBALS["connect"]->getInstance("SELECT * FROM nhaxuatban WHERE IDNXB=$id LIMIT 1");
        return $row ?: null;
    }

    public function insertNXB($ten, $diachi, $sdt) {
        $ten    = $GLOBALS["connect"]->db->quote($ten);
        $diachi = $GLOBALS["connect"]->db->quote($diachi);
        $sdt    = $GLOBALS["connect"]->db->quote($sdt);
        return $GLOBALS["connect"]->exec("INSERT INTO nhaxuatban(TenNXB,DiaChi,DienThoai) VALUES($ten,$diachi,$sdt)");
    }

    public function updateNXB($id, $ten, $diachi, $sdt) {
        $id     = (int)$id;
        $ten    = $GLOBALS["connect"]->db->quote($ten);
        $diachi = $GLOBALS["connect"]->db->quote($diachi);
        $sdt    = $GLOBALS["connect"]->db->quote($sdt);
        return $GLOBALS["connect"]->exec("UPDATE nhaxuatban SET TenNXB=$ten,DiaChi=$diachi,DienThoai=$sdt WHERE IDNXB=$id");
    }

    public function deleteNXB($id) {
        $id = (int)$id;
        return $GLOBALS["connect"]->exec("DELETE FROM nhaxuatban WHERE IDNXB=$id");
    }

    // ── Đơn hàng ──────────────────────────────────────────────

    public function getAllDonhang() {
        $sql = "SELECT donhang.*, nguoidung.HoTen, nguoidung.Email,
                       nguoidung.DienThoai, nguoidung.DiaChi
                FROM donhang
                JOIN nguoidung ON donhang.IDNguoiDung = nguoidung.IDNguoiDung
                ORDER BY donhang.IDDonHang DESC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function updateTrangThaiDonhang($id, $trangThai) {
        $id  = (int)$id;
        $tt  = (int)$trangThai;
        return $GLOBALS["connect"]->exec("UPDATE donhang SET TrangThai=$tt WHERE IDDonHang=$id");
    }

    public function getChitietDonhang($idDonhang) {
        $id  = (int)$idDonhang;
        $sql = "SELECT chitietdonhang.*, sach.TenSach
                FROM chitietdonhang
                JOIN sach ON chitietdonhang.IDSach = sach.IDSach
                WHERE IDDonHang = $id";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    // ── Người dùng ────────────────────────────────────────────

    public function getAllNguoidung() {
        return $GLOBALS["connect"]->getList("SELECT * FROM nguoidung ORDER BY IDNguoiDung DESC")->fetchAll();
    }

    public function setVaiTro($id, $vaiTro) {
        $id = (int)$id;
        $vt = (int)$vaiTro;
        return $GLOBALS["connect"]->exec("UPDATE nguoidung SET IDVaiTro=$vt WHERE IDNguoiDung=$id");
    }

    // ── Đánh giá ──────────────────────────────────────────────

    public function getAllDanhgia() {
        $sql = "SELECT danhgia.*, sach.TenSach, nguoidung.HoTen
                FROM danhgia
                JOIN sach      ON danhgia.IDSach       = sach.IDSach
                JOIN nguoidung ON danhgia.IDNguoiDung  = nguoidung.IDNguoiDung
                ORDER BY danhgia.IDDanhGia DESC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function deleteDanhgia($id) {
        $id = (int)$id;
        return $GLOBALS["connect"]->exec("DELETE FROM danhgia WHERE IDDanhGia=$id");
    }
}
