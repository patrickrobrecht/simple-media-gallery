<?php

namespace SimpleMediaGallery;

/**
 * Configuration file reader.
 *
 * @package SimpleMediaGallery
 */
class Configuration {

	public static function isCachingEnabled() {
		return defined( 'CACHE' ) && CACHE;
	}

	public static function getDataDirectoryLocalAbsolutePath() {
		return dirname( __DIR__, 1 ) . '/' . ( defined( 'DATA' ) ? DATA : 'data' ) . '/';
	}

	public static function getDataDirectoryRelativePath() {
		return ( defined( 'DATA' ) ? DATA : 'data' ) . '/';
	}

	public static function isSubDirectoryInstall() {
		return defined( 'SUBDIRECTORY' ) && SUBDIRECTORY;
	}

	public static function getSubDirectory() {
		return defined( 'SUBDIRECTORY' ) ? SUBDIRECTORY : '';
	}

	public static function getWebRoot() {
		return '/' . self::getSubDirectory();
	}

	public static function getSiteTitle() {
		return defined( 'TITLE' ) ? htmlspecialchars( TITLE ) : 'Media Gallery';
	}

	public static function getCopyright() {
		return defined( 'COPYRIGHT' ) ? htmlspecialchars( COPYRIGHT ) : '';
	}
}
