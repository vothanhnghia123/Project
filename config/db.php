<?php
class connect {
    public $db = null;

    public function __construct()
    {
        $this->db = new PDO("mysql:host=localhost;dbname=bansach", "root", "", [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"
        ]);
    }

    // cac phuong thuc select
    // select many: truy van nhieu dong
    public function getList($sql) {
        return $this->db->query($sql);
    }

    // select one: truy van mot dong
    public function getInstance($sql) {
        return $this->db->query($sql)->fetch();
    }

    // cac phuong thuc insert, update, delete
    public function exec($sql) {
        return $this->db->exec($sql);
    }

    // truy van co tham so (de phong SQL injection)
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
