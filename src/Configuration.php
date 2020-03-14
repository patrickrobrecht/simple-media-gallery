<?php

namespace SimpleMediaGallery;

/**
 * Configuration file reader.
 *
 * @package SimpleMediaGallery
 */
class Configuration {

	public static function isCachingEnabled(): bool
    {
		return defined( 'CACHE' ) && CACHE;
	}

	public static function getDataDirectoryLocalAbsolutePath(): string
    {
		return dirname( __DIR__, 1 ) . '/' . ( defined( 'DATA' ) ? DATA : 'data' ) . '/';
	}

	public static function getDataDirectoryRelativePath(): string
    {
		return ( defined( 'DATA' ) ? DATA : 'data' ) . '/';
	}

	public static function isSubDirectoryInstall(): bool
    {
		return defined( 'SUBDIRECTORY' ) && SUBDIRECTORY;
	}

	public static function getSubDirectory(): string
    {
		return defined( 'SUBDIRECTORY' ) ? SUBDIRECTORY : '';
	}

	public static function getWebRoot(): string
    {
		return '/' . self::getSubDirectory();
	}

	public static function getSiteTitle(): string
    {
		return defined( 'TITLE' ) ? htmlspecialchars( TITLE ) : 'Media Gallery';
	}

	public static function getCopyright(): string
    {
		return defined( 'COPYRIGHT' ) ? htmlspecialchars( COPYRIGHT ) : '';
	}
}
