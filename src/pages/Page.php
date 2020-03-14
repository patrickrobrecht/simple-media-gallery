<?php

namespace SimpleMediaGallery\Pages;

use SimpleMediaGallery\Configuration;

/**
 * A Page displays content.
 *
 * @package SimpleMediaGallery\Pages
 *
 * @property string $pageTitle {@see Page::getPageTitle()}
 * @property-read array $pages {@see Page::getPages()}
 * @property-read string $copyright {@see Page::getCopyright()}
 * @property-read array $menu {@see Page::getMenu()}
 * @property-read string $siteTitle {@see Page::getSiteTitle()}
 * @property-read string $root {@see Page::getRoot()}
 */
abstract class Page {
	protected $dataDirectory;
	private $pageTitle;
	private $root;

	public function __construct( $title = null ) {
		$this->dataDirectory = Configuration::getDataDirectoryLocalAbsolutePath();
		$this->pageTitle     = $title ?? $this->getSiteTitle();
		$this->root          = Configuration::getWebRoot();
	}

	protected function getPages( $directory, $includeSubPages = 0 ): array
    {
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
				$menu[] = $item;
			}
		}

		return $menu;
	}

	protected static function extractName( $name ): string
    {
		// Remove digits at the beginning.
		preg_match( "~^(\d+)~", $name, $m );
		if ( count( $m ) > 0 ) {
			$name = substr( $name, strlen( $m[0] ) );
		}

		// Replace _ and - with spaces.
        $name = str_replace(array('_', '-'), ' ', $name);

		return trim( $name );
	}

	public function getCopyright(): string
    {
		return Configuration::getCopyright();
	}

	public function getMenu(): array
    {
		return $this->getPages( $this->dataDirectory, 1 );
	}

    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    public function getSiteTitle(): string
    {
		return Configuration::getSiteTitle();
	}

	public function getRoot(): string
    {
		return $this->root;
	}
}
