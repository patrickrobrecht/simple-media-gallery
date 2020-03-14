<?php

namespace SimpleMediaGallery;

use SimpleMediaGallery\pages\DefaultPage;
use SimpleMediaGallery\pages\GalleryPage;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * The main class generating the pages.
 *
 * @package SimpleMediaGallery
 */
class Gallery {

	public function isDirectory( $path ): bool
    {
		$path = Configuration::getDataDirectoryLocalAbsolutePath() . $path;

		return file_exists( $path ) && is_dir( $path );
	}

	public function getPage( $path ): string
    {
		return $this->getTwig()->render(
			'page.html.twig',
			[ 'page' => new GalleryPage( $path ) ]
		);
	}

	public function getErrorPage( $text ): string
    {
		return $this->getTwig()->render(
			'default.html.twig',
			[ 'page' => new DefaultPage( $text, $text ) ]
		);
	}

	private function getTwig(): Environment
    {
		return new Environment(
			new FilesystemLoader( 'src/templates' ),
			[ 'cache' => false ]
		);
	}
}
