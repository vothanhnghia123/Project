<?php
// ============================================================
//  Model\Admin — Tất cả truy vấn dữ liệu cho khu vực Admin
// ============================================================

require_once BASE_PATH . '/model/master.php';

class AdminModel extends MasterModel
{
    // ── DASHBOARD ─────────────────────────────────────────────

    public function getDoanhThuHomNay(): float
    {
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(TongTien),0) AS dt
             FROM donhang WHERE DATE(NgayDat) = CURDATE()"
        );
        return (float)$stmt->fetchColumn();
    }

    public function getDoanhThuThangNay(): float
    {
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(TongTien),0) AS dt
             FROM donhang
             WHERE MONTH(NgayDat)=MONTH(CURDATE())
               AND YEAR(NgayDat)=YEAR(CURDATE())"
        );
        return (float)$stmt->fetchColumn();
    }

    public function getSoDonChoXacNhan(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM donhang WHERE TrangThai = 0"
        );
        return (int)$stmt->fetchColumn();
    }

    public function getTongSach(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM sach")->fetchColumn();
    }

    public function getTongKhachHang(): int
    {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM nguoidung WHERE IDVaiTro != 1"
        )->fetchColumn();
    }

    public function getTongDonHang(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM donhang")->fetchColumn();
    }

    /** Doanh thu 7 ngày gần nhất (dùng cho biểu đồ) */
    public function getDoanhThu7Ngay(): array
    {
        $stmt = $this->db->query(
            "SELECT DATE(NgayDat) AS ngay, COALESCE(SUM(TongTien),0) AS tong
             FROM donhang
             WHERE NgayDat >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
             GROUP BY DATE(NgayDat)
             ORDER BY ngay ASC"
        );
        return $stmt->fetchAll();
    }

    /** 5 đơn hàng mới nhất cho dashboard */
    public function getDonHangMoiNhat(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT donhang.*, nguoidung.HoTen
             FROM donhang
             JOIN nguoidung ON donhang.IDNguoiDung = nguoidung.IDNguoiDung
             ORDER BY donhang.IDDonHang DESC
             LIMIT :lim"
        );
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── SÁCH ──────────────────────────────────────────────────

    public function getAllSach(): array
    {
        return $this->db->query(
            "SELECT sach.*, theloai.TenTheLoai, nhaxuatban.TenNXB, tacgia.TenTacGia
             FROM sach
             LEFT JOIN theloai    ON sach.IDTheLoai = theloai.IDTheLoai
             LEFT JOIN nhaxuatban ON sach.IDNXB     = nhaxuatban.IDNXB
             LEFT JOIN tacgia     ON sach.IDTacGia  = tacgia.IDTacGia
             ORDER BY sach.IDSach DESC"
        )->fetchAll();
    }

    public function getSachById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM sach WHERE IDSach=:id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function insertSach(array $d, string $hinhanh): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO sach(TenSach,IDTheLoai,IDNXB,IDTacGia,GiaBan,SoLuong,MoTa,HinhAnh,SoTrang,NamXB)
             VALUES(:ts,:tl,:nxb,:tg,:gia,:sl,:mt,:ha,:stp,:namxb)"
        );
        $stmt->bindValue(':ts',   $d['tensach'],   PDO::PARAM_STR);
        $stmt->bindValue(':tl',   $d['idtheloai'], PDO::PARAM_INT);
        $stmt->bindValue(':nxb',  $d['idnxb'],     PDO::PARAM_INT);
        $stmt->bindValue(':tg',   $d['idtacgia'],  PDO::PARAM_INT);
        $stmt->bindValue(':gia',  $d['giaban'],    PDO::PARAM_STR);
        $stmt->bindValue(':sl',   $d['soluong'],   PDO::PARAM_INT);
        $stmt->bindValue(':mt',   $d['mota'],      PDO::PARAM_STR);
        $stmt->bindValue(':ha',   $hinhanh,        PDO::PARAM_STR);
        $stmt->bindValue(':stp',  $d['sotrang'],   PDO::PARAM_INT);
        $stmt->bindValue(':namxb',$d['namxb'],     PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateSach(int $id, array $d, ?string $hinhanh = null): bool
    {
        if ($hinhanh !== null) {
            $stmt = $this->db->prepare(
                "UPDATE sach SET TenSach=:ts,IDTheLoai=:tl,IDNXB=:nxb,IDTacGia=:tg,
                 GiaBan=:gia,SoLuong=:sl,MoTa=:mt,HinhAnh=:ha,SoTrang=:stp,NamXB=:namxb
                 WHERE IDSach=:id"
            );
            $stmt->bindValue(':ha', $hinhanh, PDO::PARAM_STR);
        } else {
            $stmt = $this->db->prepare(
                "UPDATE sach SET TenSach=:ts,IDTheLoai=:tl,IDNXB=:nxb,IDTacGia=:tg,
                 GiaBan=:gia,SoLuong=:sl,MoTa=:mt,SoTrang=:stp,NamXB=:namxb
                 WHERE IDSach=:id"
            );
        }
        $stmt->bindValue(':ts',   $d['tensach'],   PDO::PARAM_STR);
        $stmt->bindValue(':tl',   $d['idtheloai'], PDO::PARAM_INT);
        $stmt->bindValue(':nxb',  $d['idnxb'],     PDO::PARAM_INT);
        $stmt->bindValue(':tg',   $d['idtacgia'],  PDO::PARAM_INT);
        $stmt->bindValue(':gia',  $d['giaban'],    PDO::PARAM_STR);
        $stmt->bindValue(':sl',   $d['soluong'],   PDO::PARAM_INT);
        $stmt->bindValue(':mt',   $d['mota'],      PDO::PARAM_STR);
        $stmt->bindValue(':stp',  $d['sotrang'],   PDO::PARAM_INT);
        $stmt->bindValue(':namxb',$d['namxb'],     PDO::PARAM_STR);
        $stmt->bindValue(':id',   $id,             PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteSach(int $id): ?string
    {
        $stmt = $this->db->prepare("SELECT HinhAnh FROM sach WHERE IDSach=:id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row     = $stmt->fetch();
        $hinhanh = $row ? $row['HinhAnh'] : null;
        $del = $this->db->prepare("DELETE FROM sach WHERE IDSach=:id");
        $del->bindValue(':id', $id, PDO::PARAM_INT);
        $del->execute();
        return $hinhanh;
    }

    // ── DANH MỤC ──────────────────────────────────────────────

    public function getAllDanhmuc(): array
    {
        return $this->db->query("SELECT * FROM danhmuc ORDER BY IDDanhMuc ASC")->fetchAll();
    }

    public function getDanhmucById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM danhmuc WHERE IDDanhMuc=:id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function insertDanhmuc(string $ten): bool
    {
        $stmt = $this->db->prepare("INSERT INTO danhmuc(TenDanhMuc) VALUES(:ten)");
        $stmt->bindValue(':ten', $ten, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateDanhmuc(int $id, string $ten): bool
    {
        $stmt = $this->db->prepare("UPDATE danhmuc SET TenDanhMuc=:ten WHERE IDDanhMuc=:id");
        $stmt->bindValue(':ten', $ten, PDO::PARAM_STR);
        $stmt->bindValue(':id',  $id,  PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteDanhmuc(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM danhmuc WHERE IDDanhMuc=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ── THỂ LOẠI ──────────────────────────────────────────────

    public function getAllTheloai(): array
    {
        return $this->db->query(
            "SELECT theloai.*, danhmuc.TenDanhMuc
             FROM theloai JOIN danhmuc ON theloai.IDDanhMuc=danhmuc.IDDanhMuc
             ORDER BY theloai.IDDanhMuc, theloai.IDTheLoai"
        )->fetchAll();
    }

    public function getTheloaiById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM theloai WHERE IDTheLoai=:id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function insertTheloai(string $ten, int $idDm): bool
    {
        $stmt = $this->db->prepare("INSERT INTO theloai(TenTheLoai,IDDanhMuc) VALUES(:ten,:dm)");
        $stmt->bindValue(':ten', $ten,  PDO::PARAM_STR);
        $stmt->bindValue(':dm',  $idDm, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateTheloai(int $id, string $ten, int $idDm): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE theloai SET TenTheLoai=:ten,IDDanhMuc=:dm WHERE IDTheLoai=:id"
        );
        $stmt->bindValue(':ten', $ten,  PDO::PARAM_STR);
        $stmt->bindValue(':dm',  $idDm, PDO::PARAM_INT);
        $stmt->bindValue(':id',  $id,   PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteTheloai(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM theloai WHERE IDTheLoai=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ── TÁC GIẢ ───────────────────────────────────────────────

    public function getAllTacgia(): array
    {
        return $this->db->query("SELECT * FROM tacgia ORDER BY IDTacGia DESC")->fetchAll();
    }

    public function getTacgiaById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM tacgia WHERE IDTacGia=:id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function insertTacgia(string $ten, string $tieusu): bool
    {
        $stmt = $this->db->prepare("INSERT INTO tacgia(TenTacGia,TieuSu) VALUES(:ten,:ts)");
        $stmt->bindValue(':ten', $ten,    PDO::PARAM_STR);
        $stmt->bindValue(':ts',  $tieusu, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateTacgia(int $id, string $ten, string $tieusu): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE tacgia SET TenTacGia=:ten,TieuSu=:ts WHERE IDTacGia=:id"
        );
        $stmt->bindValue(':ten', $ten,    PDO::PARAM_STR);
        $stmt->bindValue(':ts',  $tieusu, PDO::PARAM_STR);
        $stmt->bindValue(':id',  $id,     PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteTacgia(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tacgia WHERE IDTacGia=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ── NHÀ XUẤT BẢN ──────────────────────────────────────────

    public function getAllNXB(): array
    {
        return $this->db->query("SELECT * FROM nhaxuatban ORDER BY IDNXB ASC")->fetchAll();
    }

    public function getNXBById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM nhaxuatban WHERE IDNXB=:id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function insertNXB(string $ten, string $diachi, string $sdt): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO nhaxuatban(TenNXB,DiaChi,DienThoai) VALUES(:ten,:dc,:sdt)"
        );
        $stmt->bindValue(':ten', $ten,    PDO::PARAM_STR);
        $stmt->bindValue(':dc',  $diachi, PDO::PARAM_STR);
        $stmt->bindValue(':sdt', $sdt,    PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateNXB(int $id, string $ten, string $diachi, string $sdt): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE nhaxuatban SET TenNXB=:ten,DiaChi=:dc,DienThoai=:sdt WHERE IDNXB=:id"
        );
        $stmt->bindValue(':ten', $ten,    PDO::PARAM_STR);
        $stmt->bindValue(':dc',  $diachi, PDO::PARAM_STR);
        $stmt->bindValue(':sdt', $sdt,    PDO::PARAM_STR);
        $stmt->bindValue(':id',  $id,     PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteNXB(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM nhaxuatban WHERE IDNXB=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ── ĐƠN HÀNG ──────────────────────────────────────────────

    public function getAllDonhang(): array
    {
        return $this->db->query(
            "SELECT donhang.*, nguoidung.HoTen, nguoidung.Email,
                    nguoidung.DienThoai, nguoidung.DiaChi
             FROM donhang
             JOIN nguoidung ON donhang.IDNguoiDung = nguoidung.IDNguoiDung
             ORDER BY donhang.IDDonHang DESC"
        )->fetchAll();
    }

    public function updateTrangThaiDonhang(int $id, int $trangThai): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE donhang SET TrangThai=:tt WHERE IDDonHang=:id"
        );
        $stmt->bindValue(':tt', $trangThai, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id,        PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getChitietDonhang(int $idDonhang): array
    {
        $stmt = $this->db->prepare(
            "SELECT chitietdonhang.*, sach.TenSach
             FROM chitietdonhang
             JOIN sach ON chitietdonhang.IDSach = sach.IDSach
             WHERE IDDonHang=:id"
        );
        $stmt->bindValue(':id', $idDonhang, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── NGƯỜI DÙNG ────────────────────────────────────────────

    public function getAllNguoidung(): array
    {
        return $this->db->query(
            "SELECT * FROM nguoidung ORDER BY IDNguoiDung DESC"
        )->fetchAll();
    }

    public function setVaiTro(int $id, int $vaiTro): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE nguoidung SET IDVaiTro=:vt WHERE IDNguoiDung=:id"
        );
        $stmt->bindValue(':vt', $vaiTro, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id,     PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ── ĐÁNH GIÁ ──────────────────────────────────────────────

    public function getAllDanhgia(): array
    {
        return $this->db->query(
            "SELECT danhgia.*, sach.TenSach, nguoidung.HoTen
             FROM danhgia
             JOIN sach     ON danhgia.IDSach      = sach.IDSach
             JOIN nguoidung ON danhgia.IDNguoiDung = nguoidung.IDNguoiDung
             ORDER BY danhgia.IDDanhGia DESC"
        )->fetchAll();
    }

    public function deleteDanhgia(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM danhgia WHERE IDDanhGia=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
