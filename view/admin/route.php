<?php
// them lop controller
require_once "controller/{$controller}.php";

// kiem soat yeu cau
switch ($controller) {
    case 'AdminHome':
        $controller = new AdminHome();
        break;
    case 'AdminBook':
        $controller = new AdminBook();
        break;
    case 'AdminDanhmuc':
        $controller = new AdminDanhmuc();
        break;
    case 'AdminTheloai':
        $controller = new AdminTheloai();
        break;
    case 'AdminTacgia':
        $controller = new AdminTacgia();
        break;
    case 'AdminNxb':
        $controller = new AdminNxb();
        break;
    case 'AdminDonhang':
        $controller = new AdminDonhang();
        break;
    case 'AdminNguoidung':
        $controller = new AdminNguoidung();
        break;
    case 'AdminDanhgia':
        $controller = new AdminDanhgia();
        break;
}

$controller->{$action}();
