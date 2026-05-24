<?php
// ============================================================
//  Model\Book — Truy vấn CSDL liên quan đến Sách
// ============================================================

require_once BASE_PATH . '/model/master.php';

class BookModel extends MasterModel
{
    // ── Trang chủ ──────────────────────────────────────────

    /**
     * Lấy danh sách sách mới nhất (cho carousel trang chủ)
     */
    public function getNewBooks(int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM sach ORDER BY NgayNhap DESC LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy sách theo tên danh mục (dùng cho Văn học, Thiếu nhi,...)
     */
    public function getBooksByDanhmucName(string $tenDanhmuc, int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            'SELECT sach.*
             FROM sach
             JOIN theloai ON sach.IDTheLoai = theloai.IDTheLoai
             JOIN danhmuc ON theloai.IDDanhMuc = danhmuc.IDDanhMuc
             WHERE danhmuc.TenDanhMuc = :ten
             ORDER BY RAND()
             LIMIT :limit'
        );
        $stmt->bindValue(':ten',   $tenDanhmuc,   PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit,         PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy sách gợi ý ngẫu nhiên
     */
    public function getRandomBooks(int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM sach ORDER BY RAND() LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Tìm kiếm ───────────────────────────────────────────

    /**
     * Tìm kiếm live (gợi ý nhanh, giới hạn 5 kết quả)
     */
    public function searchLive(string $keyword, int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT sach.*, tacgia.TenTacGia
            FROM sach
            LEFT JOIN tacgia ON sach.IDTacGia = tacgia.IDTacGia
            WHERE sach.TenSach LIKE :kw1
                OR tacgia.TenTacGia LIKE :kw2
            LIMIT :limit'
        );

        $kw = '%' . $keyword . '%';

        $stmt->bindValue(':kw1', $kw, PDO::PARAM_STR);
        $stmt->bindValue(':kw2', $kw, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm đầy đủ (trang kết quả tìm kiếm)
     */
    public function searchFull(string $keyword): array
    {
        $stmt = $this->db->prepare(
            'SELECT sach.*, tacgia.TenTacGia
             FROM sach
             LEFT JOIN tacgia ON sach.IDTacGia = tacgia.IDTacGia
             WHERE sach.TenSach LIKE :kw1 OR tacgia.TenTacGia LIKE :kw2
             ORDER BY sach.IDSach DESC'
        );
        $kw = '%' . $keyword . '%';
        
        $stmt->bindValue(':kw1', $kw, PDO::PARAM_STR);
    $stmt->bindValue(':kw2', $kw, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ─────────────────────────────────────────────────────────────────
    //  CÁC METHOD BỔ SUNG CHO NHÓM BOOK (sanpham, singleproduct, cart)
    // ─────────────────────────────────────────────────────────────────

    /**
     * Đếm tổng số sách (dùng cho phân trang)
     * Lọc theo danh mục, thể loại hoặc lấy tất cả
     */
    public function countBooks(?int $idDanhmuc = null, ?int $idTheloai = null): int
    {
        if ($idDanhmuc !== null) {
            $stmt = $this->db->prepare(
                'SELECT COUNT(*) FROM sach
                 JOIN theloai ON sach.IDTheLoai = theloai.IDTheLoai
                 WHERE theloai.IDDanhMuc = :id'
            );
            $stmt->bindValue(':id', $idDanhmuc, PDO::PARAM_INT);
        } elseif ($idTheloai !== null) {
            $stmt = $this->db->prepare(
                'SELECT COUNT(*) FROM sach WHERE IDTheLoai = :id'
            );
            $stmt->bindValue(':id', $idTheloai, PDO::PARAM_INT);
        } else {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM sach');
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Lấy danh sách sách có phân trang, lọc theo danh mục / thể loại
     */
    public function getBooksPaginated(
        int $offset,
        int $limit,
        ?int $idDanhmuc = null,
        ?int $idTheloai = null
    ): array {
        if ($idDanhmuc !== null) {
            $stmt = $this->db->prepare(
                'SELECT sach.* FROM sach
                 JOIN theloai ON sach.IDTheLoai = theloai.IDTheLoai
                 WHERE theloai.IDDanhMuc = :id
                 ORDER BY sach.IDSach DESC
                 LIMIT :lim OFFSET :off'
            );
            $stmt->bindValue(':id',  $idDanhmuc, PDO::PARAM_INT);
        } elseif ($idTheloai !== null) {
            $stmt = $this->db->prepare(
                'SELECT sach.* FROM sach
                 WHERE IDTheLoai = :id
                 ORDER BY IDSach DESC
                 LIMIT :lim OFFSET :off'
            );
            $stmt->bindValue(':id', $idTheloai, PDO::PARAM_INT);
        } else {
            $stmt = $this->db->prepare(
                'SELECT * FROM sach
                 ORDER BY IDSach DESC
                 LIMIT :lim OFFSET :off'
            );
        }
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Chi tiết một cuốn sách (JOIN tác giả, thể loại, NXB)
     */
    public function getBookDetail(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT sach.*,
                    tacgia.TenTacGia,
                    theloai.TenTheLoai,
                    nhaxuatban.TenNXB
             FROM sach
             LEFT JOIN tacgia     ON sach.IDTacGia = tacgia.IDTacGia
             LEFT JOIN theloai    ON sach.IDTheLoai = theloai.IDTheLoai
             LEFT JOIN nhaxuatban ON sach.IDNXB     = nhaxuatban.IDNXB
             WHERE sach.IDSach = :id
             LIMIT 1'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Thống kê đánh giá sao của một cuốn sách
     */
    public function getRatingSummary(int $idSach): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                COUNT(*)                                              AS tong,
                ROUND(AVG(SoSao), 1)                                  AS trungbinh,
                SUM(CASE WHEN SoSao = 5 THEN 1 ELSE 0 END)           AS sao5,
                SUM(CASE WHEN SoSao = 4 THEN 1 ELSE 0 END)           AS sao4,
                SUM(CASE WHEN SoSao = 3 THEN 1 ELSE 0 END)           AS sao3,
                SUM(CASE WHEN SoSao = 2 THEN 1 ELSE 0 END)           AS sao2,
                SUM(CASE WHEN SoSao = 1 THEN 1 ELSE 0 END)           AS sao1
             FROM danhgia
             WHERE IDSach = :id'
        );
        $stmt->bindValue(':id', $idSach, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Lấy danh sách đánh giá mới nhất của một cuốn sách
     */
    public function getReviews(int $idSach, int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT danhgia.*, nguoidung.HoTen
             FROM danhgia
             LEFT JOIN nguoidung ON danhgia.IDNguoiDung = nguoidung.IDNguoiDung
             WHERE danhgia.IDSach = :id
             ORDER BY danhgia.NgayDanhGia DESC
             LIMIT :lim'
        );
        $stmt->bindValue(':id',  $idSach, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lưu đánh giá mới
     */
    public function saveReview(int $idSach, int $idNguoiDung, int $soSao, string $noiDung): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO danhgia (IDSach, IDNguoiDung, SoSao, NoiDung)
             VALUES (:idSach, :idND, :soSao, :noidung)'
        );
        $stmt->bindValue(':idSach',  $idSach,      PDO::PARAM_INT);
        $stmt->bindValue(':idND',    $idNguoiDung, PDO::PARAM_INT);
        $stmt->bindValue(':soSao',   $soSao,       PDO::PARAM_INT);
        $stmt->bindValue(':noidung', $noiDung,     PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Lấy thông tin sách theo mảng ID (dùng cho giỏ hàng)
     */
    public function getBooksByIds(array $ids): array
    {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare(
            "SELECT IDSach, TenSach, GiaBan, HinhAnh, SoLuong
             FROM sach
             WHERE IDSach IN ($placeholders)"
        );
        $stmt->execute($ids);
        // Index theo IDSach để tra cứu nhanh
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['IDSach']] = $row;
        }
        return $result;
    }

    /**
     * Lấy giá một cuốn sách (dùng khi tính tổng tiền đặt hàng)
     */
    public function getBookPrice(int $idSach): ?float
    {
        $stmt = $this->db->prepare(
            'SELECT GiaBan, SoLuong FROM sach WHERE IDSach = :id LIMIT 1'
        );
        $stmt->bindValue(':id', $idSach, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? (float)$row['GiaBan'] : null;
    }

    /**
     * Tạo đơn hàng + chi tiết + trừ kho (gói trong transaction)
     */
    public function placeOrder(
        int    $idNguoiDung,
        array  $ids,
        array  $quantities,
        string $phuongThucTT
    ): int|false {
        try {
            $this->db->beginTransaction();

            // 1. Tính tổng tiền & kiểm tra kho
            $tongTien = 0;
            $books    = $this->getBooksByIds($ids);

            foreach ($ids as $i => $idSach) {
                $sl = (int)$quantities[$i];
                if (!isset($books[$idSach])) {
                    $this->db->rollBack();
                    return false;
                }
                if ($books[$idSach]['SoLuong'] < $sl) {
                    $this->db->rollBack();
                    return false;
                }
                $tongTien += $books[$idSach]['GiaBan'] * $sl;
            }

            // 2. Insert đơn hàng
            $stmtDH = $this->db->prepare(
                'INSERT INTO donhang (IDNguoiDung, NgayDat, TongTien, TrangThai, PhuongThucTT)
                 VALUES (:idND, NOW(), :tong, 0, :tt)'
            );
            $stmtDH->bindValue(':idND', $idNguoiDung,  PDO::PARAM_INT);
            $stmtDH->bindValue(':tong', $tongTien,     PDO::PARAM_STR);
            $stmtDH->bindValue(':tt',   $phuongThucTT, PDO::PARAM_STR);
            $stmtDH->execute();
            $idDonHang = (int)$this->db->lastInsertId();

            // 3. Insert chi tiết + trừ kho
            $stmtCT = $this->db->prepare(
                'INSERT INTO chitietdonhang (IDDonHang, IDSach, SoLuong, DonGia)
                 VALUES (:idDH, :idS, :sl, :gia)'
            );
            $stmtTru = $this->db->prepare(
                'UPDATE sach SET SoLuong = SoLuong - :sl WHERE IDSach = :id'
            );

            foreach ($ids as $i => $idSach) {
                $sl    = (int)$quantities[$i];
                $donGia = $books[$idSach]['GiaBan'];

                $stmtCT->bindValue(':idDH', $idDonHang, PDO::PARAM_INT);
                $stmtCT->bindValue(':idS',  $idSach,    PDO::PARAM_INT);
                $stmtCT->bindValue(':sl',   $sl,         PDO::PARAM_INT);
                $stmtCT->bindValue(':gia',  $donGia,     PDO::PARAM_STR);
                $stmtCT->execute();

                $stmtTru->bindValue(':sl', $sl,      PDO::PARAM_INT);
                $stmtTru->bindValue(':id', $idSach,  PDO::PARAM_INT);
                $stmtTru->execute();
            }

            $this->db->commit();
            return $idDonHang;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('[placeOrder Error] ' . $e->getMessage());
            return false;
        }
    }

    // ── Methods bổ sung cho Home controller ──────────────────

    /**
     * Lấy tất cả danh mục (dùng cho mega-menu header)
     */
    public function getAllCategories(): array
    {
        return $this->db->query(
            'SELECT * FROM danhmuc ORDER BY IDDanhMuc ASC'
        )->fetchAll();
    }

    /**
     * Lấy thể loại con theo IDDanhMuc (dùng cho mega-menu)
     */
    public function getSubCategoriesByDanhMuc(int $idDanhmuc): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM theloai WHERE IDDanhMuc = :id ORDER BY IDTheLoai ASC'
        );
        $stmt->bindValue(':id', $idDanhmuc, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy sách theo tên danh mục (alias, dùng trong Home::index)
     */
    public function getBooksByCategory(string $tenDanhmuc, int $limit = 10): array
    {
        return $this->getBooksByDanhmucName($tenDanhmuc, $limit);
    }

    /**
     * Lấy IDDanhMuc theo tên (dùng trong Home::index để render link "Xem thêm")
     */
    public function getCategoryIdByName(string $tenDanhmuc): int
    {
        $stmt = $this->db->prepare(
            'SELECT IDDanhMuc FROM danhmuc WHERE TenDanhMuc = :ten LIMIT 1'
        );
        $stmt->bindValue(':ten', $tenDanhmuc, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? (int)$row['IDDanhMuc'] : 0;
    }
}
