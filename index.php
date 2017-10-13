<?php
if (file_exists(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
}
require __DIR__ . '/vendor/autoload.php';

use SimpleMediaGallery\Gallery;

$url = substr($_SERVER['REQUEST_URI'], 1);
$cacheFilePath = __DIR__ . '/' . $url;

$gallery = new Gallery();

// Return 404 page for invalid requests.
$error = false;
if (substr($url, -5) === '.html') {
    $url = ($url === 'index.html') ? '' : substr($url, 0, strlen($url) - 5);
    if (!$gallery->isDirectory($url)) {
        $error = true;
    }
} else {
    $error = true;
}

if ($error) {
    header("HTTP/1.0 404 Not Found");
    echo $gallery->getErrorPage('Page Not Found');
} else {
    // Serve cached version if cache is active.
    if (defined('CACHE') && CACHE && file_exists($cacheFilePath)) {
        echo '<!-- Served from cache -->';
        echo file_get_contents($cacheFilePath);
    } else {
        $page = $gallery->getPage($url);
        echo $page;

        // Create new cache file.
        $file = fopen($cacheFilePath, 'w+');
        if ($file) {
            fwrite($file, $page);
            fclose($file);
        }
    }
}
