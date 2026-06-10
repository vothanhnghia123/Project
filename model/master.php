<?php
class MasterModel {
    public function __construct()
    {
    }

    public function get_all_from($table) {
        $sql = "SELECT * FROM {$table}";
        return $GLOBALS["connect"]->getList($sql);
    }
}
