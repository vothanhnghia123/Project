<?php
/**
 * project/index.php
 * ─────────────────────────────────────────────
 * Bootstrap – Điểm khởi đầu DUY NHẤT của ứng dụng.
 * Mọi request đều đi qua đây nhờ .htaccess rewrite.
 */

session_start();

// ── Đường dẫn & URL gốc ──────────────────────────────────────────────
define('BASE_PATH', __DIR__);
define('BASE_URL',  '/project');   // Thay '/project' nếu deploy ở thư mục khác

// ── Autoload Controller & Model ──────────────────────────────────────
spl_autoload_register(function (string $class): void {
    $paths = [
        BASE_PATH . '/controller/' . $class . '.php',
        BASE_PATH . '/model/'      . $class . '.php',
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ── Parse URL ────────────────────────────────────────────────────────
// .htaccess đẩy URL thực vào $_GET['url']
// VD: /project/book/detail/5  →  $_GET['url'] = 'book/detail/5'
$rawUrl   = $_GET['url'] ?? '';
$segments = array_values(array_filter(explode('/', trim($rawUrl, '/'))));

$controllerName = isset($segments[0]) && $segments[0] !== ''
    ? ucfirst(strtolower($segments[0]))   // 'book' → 'Book'
    : 'Home';

$actionName = isset($segments[1]) && $segments[1] !== ''
    ? strtolower($segments[1])            // 'detail' → 'detail'
    : 'index';

$params = array_slice($segments, 2);      // ['5'] hoặc []

// ── Load & Dispatch ──────────────────────────────────────────────────
$controllerFile = BASE_PATH . '/controller/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    die("<h2 style='font-family:sans-serif'>404 – Không tìm thấy trang <code>{$controllerName}</code>.</h2>");
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    http_response_code(500);
    die("<h2 style='font-family:sans-serif'>500 – Class <code>{$controllerName}</code> không hợp lệ.</h2>");
}

$controller = new $controllerName();

if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    die("<h2 style='font-family:sans-serif'>404 – Action <code>{$controllerName}::{$actionName}()</code> không tồn tại.</h2>");
}

call_user_func_array([$controller, $actionName], $params);
