<?php

namespace SimpleMediaGallery;

use SimpleMediaGallery\pages\DefaultPage;
use SimpleMediaGallery\pages\GalleryPage;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * The main class generating the pages.
 *
 * @package SimpleMediaGallery
 */
class Gallery {
	private $dataDirectory;

	public function __construct() {
		$this->dataDirectory = defined( 'DATA' ) ? DATA : 'data';
	}

	public function isDirectory( $path ) {
		$path = $this->dataDirectory . '/' . $path;

		return file_exists( $path ) && is_dir( $path );
	}

	public function getPage( $path ) {
		return $this->getTwig()->render(
			'page.html.twig',
			[ 'page' => new GalleryPage( $path ) ]
		);
	}

	public function getErrorPage( $text ) {
		return $this->getTwig()->render(
			'default.html.twig',
			[ 'page' => new DefaultPage( $text, $text ) ]
		);
	}

	private function getTwig() {
		return new Twig_Environment(
			new Twig_Loader_Filesystem( 'src/templates' ),
			[ 'cache' => false ]
		);
	}
}
