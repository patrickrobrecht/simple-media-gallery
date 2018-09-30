<?php

namespace SimpleMediaGallery\Pages;

/**
 * A Page displays content.
 *
 * @package SimpleMediaGallery\Pages
 */
abstract class Page {
	protected $dataDirectory;
	private $copyright;
	private $pageTitle;
	private $siteTitle;
	private $root;

	public function __construct( $title = null ) {
		$this->dataDirectory = dirname(__DIR__, 2) . '/' . (defined( 'DATA' ) ? DATA : 'data') . '/';
		$this->copyright     = defined( 'COPYRIGHT' ) ? htmlspecialchars( COPYRIGHT ) : '';
		$this->siteTitle     = defined( 'TITLE' ) ? htmlspecialchars( TITLE ) : 'Media Gallery';
		$this->pageTitle     = is_null( $title ) ? $this->siteTitle : $title;
		$this->root          = '/' . ( defined( 'SUBDIRECTORY' ) ? SUBDIRECTORY : '' );
	}

	protected function getSubPages( $directory ) {
		$menu = [];
		foreach ( glob( $directory . '*' ) as $file ) {
			if ( is_dir( $file ) ) {
				$name = self::extractName( pathinfo( $file, PATHINFO_FILENAME ) );
				$item = [
					'name' => $name,
					'link' => str_replace( $this->dataDirectory, '', $file ) . '/'
				];
				array_push( $menu, $item );
			}
		}

		return $menu;
	}

	protected static function extractName( $name ) {
		// Remove digits at the beginning.
		preg_match( "~^(\d+)~", $name, $m );
		if ( count( $m ) > 0 ) {
			$name = substr( $name, strlen( $m[0] ) );
		}

		// Replace _ and - with spaces.
		$name = str_replace( '_', ' ', $name );
		$name = str_replace( '-', ' ', $name );

		return trim( $name );
	}

	public function getCopyright() {
		return $this->copyright;
	}

	public function getMenu() {
		return $this->getSubPages( $this->dataDirectory );
	}

	public function getPageTitle() {
		return $this->pageTitle;
	}

	public function getSiteTitle() {
		return $this->siteTitle;
	}

	public function getRoot() {
		return $this->root;
	}
}
