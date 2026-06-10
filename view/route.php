<?php
// them lop controller
require_once "controller/{$controller}.php";

// kiem soat yeu cau
switch ($controller) {
    case 'Home':
        $controller = new Home();
        break;
    case 'Book':
        $controller = new Book();
        break;
    case 'User':
        $controller = new User();
        break;
}

$controller->{$action}();
