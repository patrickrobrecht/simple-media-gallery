<?php
if ( file_exists( __DIR__ . '/config.php' ) ) {
	require __DIR__ . '/config.php';
}
require __DIR__ . '/vendor/autoload.php';

use SimpleMediaGallery\Gallery;
use SimpleMediaGallery\Configuration;

// Determine path relative to the root directory.
$path = substr( $_SERVER['REQUEST_URI'], 1 );
if ( Configuration::isSubDirectoryInstall() ) {
	$path = preg_replace(
		'/' . preg_quote( Configuration::getSubDirectory(), '/' ) . '/',
		'',
		$path,
		1
	);
}

$gallery = new Gallery();
if ( ! $gallery->isDirectory( $path ) ) {
	// Return 404 page for invalid requests.
	header('HTTP/1.0 404 Not Found');
	echo $gallery->getErrorPage( 'Page Not Found' );
} else {
	$cacheFilePath = __DIR__ . '/cache/' . $path;
	$cacheFile     = $cacheFilePath . 'index.html';

	// Serve cached version if cache is active.
	if ( Configuration::isCachingEnabled() && file_exists( $cacheFile ) ) {
		echo file_get_contents( $cacheFile );
		echo '<!-- Served from cache -->';
	} else {
		$page = $gallery->getPage( $path );
		echo $page;

		// Create new cache file.
		if ( ! is_dir( $cacheFilePath ) ) {
            if ( ! mkdir($cacheFilePath, 0400, true) && ! is_dir($cacheFilePath)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $cacheFilePath));
            }
		}
		$file = fopen( $cacheFile, 'wb+');
		if ( $file ) {
			fwrite( $file, $page );
			fclose( $file );
		}
	}
}
