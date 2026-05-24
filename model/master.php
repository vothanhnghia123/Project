<?php
/**
 * model/master.php
 * ─────────────────────────────────────────────────────────────
 * Base Model – tất cả Model đều kế thừa class này.
 * Cung cấp $this->db (PDO) + các helper fetchAll / fetchOne /
 * execute / lastInsertId để tránh lặp boilerplate.
 *
 * QUY ƯỚC ĐẶT TÊN (áp dụng TOÀN BỘ hệ thống):
 *   - Class Model  : PascalCase + hậu tố "Model"  → BookModel, AuthModel
 *   - Class khác   : PascalCase không hậu tố       → News, Home, Book, Auth
 *   - Method public: camelCase                      → getAllDanhmuc()
 *   - Cột DB       : giữ nguyên tên gốc DB (PascalCase tiếng Việt)
 *   - Biến PHP     : camelCase                      → $idSach, $tongTien
 */

require_once BASE_PATH . '/config/db.php';

class MasterModel
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── SELECT nhiều hàng ─────────────────────────────────────
    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ── SELECT đúng 1 hàng ────────────────────────────────────
    protected function fetchOne(string $sql, array $params = []): array|false
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    // ── INSERT / UPDATE / DELETE ──────────────────────────────
    protected function execute(string $sql, array $params = []): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    // ── ID vừa INSERT ─────────────────────────────────────────
    protected function lastInsertId(): string
    {
        return $this->db->lastInsertId();
    }
}
