<?php

namespace SimpleMediaGallery\Pages;

use SimpleMediaGallery\Configuration;

/**
 * A Page displays content.
 *
 * @package SimpleMediaGallery\Pages
 */
abstract class Page {
	protected $dataDirectory;
	private $pageTitle;
	private $root;

	public function __construct( $title = null ) {
		$this->dataDirectory = Configuration::getDataDirectoryLocalAbsolutePath();
		$this->pageTitle     = is_null( $title ) ? $this->getSiteTitle() : $title;
		$this->root          = Configuration::getWebRoot();
	}

	protected function getPages( $directory, $includeSubPages = 0 ) {
		$menu = [];
		foreach ( glob( $directory . '*' ) as $file ) {
			if ( is_dir( $file ) ) {
				$name = self::extractName( pathinfo( $file, PATHINFO_FILENAME ) );
				$item = [
					'name' => $name,
					'link' => str_replace( $this->dataDirectory, '', $file ) . '/'
				];
				if ( $includeSubPages > 0 ) {
					$item['menu'] = $this->getPages( $file. '/', $includeSubPages - 1 );
				}
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
		return Configuration::getCopyright();
	}

	public function getMenu() {
		return $this->getPages( $this->dataDirectory, 1 );
	}

	public function getPageTitle() {
		return $this->pageTitle;
	}

	public function getSiteTitle() {
		return Configuration::getSiteTitle();
	}

	public function getRoot() {
		return $this->root;
	}
}
