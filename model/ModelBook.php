<?php
class ModelBook extends MasterModel {

    // ── Trang chủ ──────────────────────────────────────────

    public function getNewBooks($limit = 8) {
        $sql = "SELECT * FROM sach ORDER BY NgayNhap DESC LIMIT $limit";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function getBooksByCategory($tenDanhmuc, $limit = 10) {
        $ten = $GLOBALS["connect"]->db->quote($tenDanhmuc);
        $sql = "SELECT sach.*
                FROM sach
                JOIN theloai ON sach.IDTheLoai = theloai.IDTheLoai
                JOIN danhmuc ON theloai.IDDanhMuc = danhmuc.IDDanhMuc
                WHERE danhmuc.TenDanhMuc = $ten
                ORDER BY RAND()
                LIMIT $limit";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function getCategoryIdByName($tenDanhmuc) {
        $ten = $GLOBALS["connect"]->db->quote($tenDanhmuc);
        $sql = "SELECT IDDanhMuc FROM danhmuc WHERE TenDanhMuc = $ten LIMIT 1";
        $row = $GLOBALS["connect"]->getInstance($sql);
        return $row ? (int)$row["IDDanhMuc"] : 0;
    }

    public function getRandomBooks($limit = 10) {
        $sql = "SELECT * FROM sach ORDER BY RAND() LIMIT $limit";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM danhmuc ORDER BY IDDanhMuc ASC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function getSubCategoriesByDanhMuc($idDanhmuc) {
        $id  = (int)$idDanhmuc;
        $sql = "SELECT * FROM theloai WHERE IDDanhMuc = $id ORDER BY IDTheLoai ASC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    // ── Tìm kiếm ───────────────────────────────────────────

    public function searchLive($keyword, $limit = 5) {
        $kw  = $GLOBALS["connect"]->db->quote('%' . $keyword . '%');
        $sql = "SELECT sach.*, tacgia.TenTacGia
                FROM sach
                LEFT JOIN tacgia ON sach.IDTacGia = tacgia.IDTacGia
                WHERE sach.TenSach LIKE $kw OR tacgia.TenTacGia LIKE $kw
                LIMIT $limit";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function searchFull($keyword) {
        $kw  = $GLOBALS["connect"]->db->quote('%' . $keyword . '%');
        $sql = "SELECT sach.*, tacgia.TenTacGia
                FROM sach
                LEFT JOIN tacgia ON sach.IDTacGia = tacgia.IDTacGia
                WHERE sach.TenSach LIKE $kw OR tacgia.TenTacGia LIKE $kw
                ORDER BY sach.IDSach DESC";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    // ── Danh sách & phân trang ──────────────────────────────

    public function countBooks($idDanhmuc = null, $idTheloai = null) {
        if ($idDanhmuc !== null) {
            $id  = (int)$idDanhmuc;
            $sql = "SELECT COUNT(*) FROM sach
                    JOIN theloai ON sach.IDTheLoai = theloai.IDTheLoai
                    WHERE theloai.IDDanhMuc = $id";
        } elseif ($idTheloai !== null) {
            $id  = (int)$idTheloai;
            $sql = "SELECT COUNT(*) FROM sach WHERE IDTheLoai = $id";
        } else {
            $sql = "SELECT COUNT(*) FROM sach";
        }
        return (int)$GLOBALS["connect"]->getList($sql)->fetchColumn();
    }

    public function getBooksPaginated($offset, $limit, $idDanhmuc = null, $idTheloai = null) {
        $off = (int)$offset;
        $lim = (int)$limit;
        if ($idDanhmuc !== null) {
            $id  = (int)$idDanhmuc;
            $sql = "SELECT sach.* FROM sach
                    JOIN theloai ON sach.IDTheLoai = theloai.IDTheLoai
                    WHERE theloai.IDDanhMuc = $id
                    ORDER BY sach.IDSach DESC
                    LIMIT $lim OFFSET $off";
        } elseif ($idTheloai !== null) {
            $id  = (int)$idTheloai;
            $sql = "SELECT sach.* FROM sach
                    WHERE IDTheLoai = $id
                    ORDER BY IDSach DESC
                    LIMIT $lim OFFSET $off";
        } else {
            $sql = "SELECT * FROM sach ORDER BY IDSach DESC LIMIT $lim OFFSET $off";
        }
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    // ── Chi tiết sách ───────────────────────────────────────

    public function getBookDetail($id) {
        $id  = (int)$id;
        $sql = "SELECT sach.*, tacgia.TenTacGia, theloai.TenTheLoai, nhaxuatban.TenNXB
                FROM sach
                LEFT JOIN tacgia     ON sach.IDTacGia  = tacgia.IDTacGia
                LEFT JOIN theloai    ON sach.IDTheLoai  = theloai.IDTheLoai
                LEFT JOIN nhaxuatban ON sach.IDNXB      = nhaxuatban.IDNXB
                WHERE sach.IDSach = $id
                LIMIT 1";
        $row = $GLOBALS["connect"]->getInstance($sql);
        return $row ?: null;
    }

    // ── Đánh giá ───────────────────────────────────────────

    public function getRatingSummary($idSach) {
        $id  = (int)$idSach;
        $sql = "SELECT
                    COUNT(*)                                        AS tong,
                    ROUND(AVG(SoSao), 1)                           AS trungbinh,
                    SUM(CASE WHEN SoSao = 5 THEN 1 ELSE 0 END)    AS sao5,
                    SUM(CASE WHEN SoSao = 4 THEN 1 ELSE 0 END)    AS sao4,
                    SUM(CASE WHEN SoSao = 3 THEN 1 ELSE 0 END)    AS sao3,
                    SUM(CASE WHEN SoSao = 2 THEN 1 ELSE 0 END)    AS sao2,
                    SUM(CASE WHEN SoSao = 1 THEN 1 ELSE 0 END)    AS sao1
                FROM danhgia WHERE IDSach = $id";
        return $GLOBALS["connect"]->getInstance($sql);
    }

    public function getReviews($idSach, $limit = 5) {
        $id  = (int)$idSach;
        $lim = (int)$limit;
        $sql = "SELECT danhgia.*, nguoidung.HoTen
                FROM danhgia
                LEFT JOIN nguoidung ON danhgia.IDNguoiDung = nguoidung.IDNguoiDung
                WHERE danhgia.IDSach = $id
                ORDER BY danhgia.NgayDanhGia DESC
                LIMIT $lim";
        return $GLOBALS["connect"]->getList($sql)->fetchAll();
    }

    public function saveReview($idSach, $idNguoiDung, $soSao, $noiDung) {
        $id  = (int)$idSach;
        $uid = (int)$idNguoiDung;
        $sao = (int)$soSao;
        $nd  = $GLOBALS["connect"]->db->quote($noiDung);
        $sql = "INSERT INTO danhgia (IDSach, IDNguoiDung, SoSao, NoiDung)
                VALUES ($id, $uid, $sao, $nd)";
        return $GLOBALS["connect"]->exec($sql);
    }

    // ── Giỏ hàng ───────────────────────────────────────────

    public function getBooksByIds($ids) {
        if (empty($ids)) return [];
        $list = implode(',', array_map('intval', $ids));
        $sql  = "SELECT IDSach, TenSach, GiaBan, HinhAnh, SoLuong
                 FROM sach WHERE IDSach IN ($list)";
        $rows = $GLOBALS["connect"]->getList($sql)->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['IDSach']] = $row;
        }
        return $result;
    }

    // ── Đặt hàng ───────────────────────────────────────────

    public function placeOrder($idNguoiDung, $ids, $quantities, $phuongThucTT) {
        $db = $GLOBALS["connect"]->db;
        try {
            $db->beginTransaction();

            $books    = $this->getBooksByIds($ids);
            $tongTien = 0;

            foreach ($ids as $i => $idSach) {
                $sl = (int)$quantities[$i];
                if (!isset($books[$idSach]) || $books[$idSach]['SoLuong'] < $sl) {
                    $db->rollBack();
                    return false;
                }
                $tongTien += $books[$idSach]['GiaBan'] * $sl;
            }

            $uid = (int)$idNguoiDung;
            $tt  = $db->quote($phuongThucTT);
            $db->exec("INSERT INTO donhang (IDNguoiDung, NgayDat, TongTien, TrangThai, PhuongThucTT)
                       VALUES ($uid, NOW(), $tongTien, 0, $tt)");
            $idDonHang = (int)$db->lastInsertId();

            foreach ($ids as $i => $idSach) {
                $sl     = (int)$quantities[$i];
                $idS    = (int)$idSach;
                $donGia = (float)$books[$idSach]['GiaBan'];
                $db->exec("INSERT INTO chitietdonhang (IDDonHang, IDSach, SoLuong, DonGia)
                           VALUES ($idDonHang, $idS, $sl, $donGia)");
                $db->exec("UPDATE sach SET SoLuong = SoLuong - $sl WHERE IDSach = $idS");
            }

            $db->commit();
            return $idDonHang;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
