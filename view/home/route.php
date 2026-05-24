<?php
/**
 * view/home/route.php
 * Helper sinh URL nội bộ cho các view thuộc nhóm Home.
 * Dùng: url('book/detail', 5)  → /project/book/detail/5
 */

if (!function_exists('url')) {
    function url(string $path = '', ...$params): string
    {
        $base = rtrim(BASE_URL, '/');
        $path = ltrim($path, '/');
        $extra = implode('/', array_map('rawurlencode', $params));
        return $base . '/' . $path . ($extra ? '/' . $extra : '');
    }
}
