<?php

namespace SimpleMediaGallery\pages;

/**
 * A DefaultPage displays text.
 *
 * @package SimpleMediaGallery\pages
 */
class DefaultPage extends Page {
	private $text;

	public function __construct( $title, $text ) {
		parent::__construct( $title );
		$this->text = $text;
	}

	public function getText() {
		return $this->text;
	}
}
