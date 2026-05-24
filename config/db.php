<?php
/**
 * config/db.php
 * Kết nối CSDL bằng PDO – Singleton Pattern
 * CSDL gốc: bansach (giữ nguyên tên từ dự án cũ)
 */
class Database
{
    private static ?PDO $instance = null;

    private static string $host    = 'localhost';
    private static string $dbname  = 'bansach';
    private static string $user    = 'root';
    private static string $pass    = '';
    private static string $charset = 'utf8mb4';

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::$host,
                self::$dbname,
                self::$charset
            );
            try {
                self::$instance = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                error_log('[DB Error] ' . $e->getMessage());
                die('<h2 style="font-family:sans-serif;color:red;">Không thể kết nối cơ sở dữ liệu.</h2>');
            }
        }
        return self::$instance;
    }
}
