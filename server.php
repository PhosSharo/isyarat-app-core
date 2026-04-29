<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * Router script for PHP built-in server.
 * Usage: php -S localhost:6000 server.php
 *
 * This allows running without -t public flag.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/'
);

// If the request is for a real file in public/, serve it directly
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Otherwise, route everything through public/index.php
require_once __DIR__.'/public/index.php';
