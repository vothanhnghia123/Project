<?php
ob_start();
session_start();

require_once "config/db.php";
global $connect;
$connect = new connect();

require_once "model/master.php";

// tiep nhan tham so controller va action
if (isset($_GET["controller"], $_GET["action"])) {
    $controller = $_GET["controller"];
    $action     = $_GET["action"];
} else {
    $controller = "Home";
    $action     = "index";
}

// tham so them (vi du: id)
$param = $_GET["param"] ?? null;

// AJAX add cart
if ($controller === 'Book' && $action === 'addcart') {
    require_once "controller/Book.php";

    $book = new Book();
    $book->addcart();

    exit;
}

// Live Search AJAX
if ($controller === 'Home' && $action === 'livesearch') {
    require_once "controller/Home.php";

    $home = new Home();
    $home->livesearch();

    exit;
}
// chuyen tiep toi layout tuong ung
if (strpos($controller, "Admin") !== false) {
    require_once "view/admin/layout.php"; // layout trang admin
} else {
    require_once "view/layout.php"; // layout trang nguoi dung
}
