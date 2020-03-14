<?php

namespace SimpleMediaGallery\pages;

use SimpleMediaGallery\Configuration;

/**
 * A GalleryPage displays the media within one directory.
 *
 * @package SimpleMediaGallery\pages
 *
 * @property-read $children {@see GalleryPage::getChildren()}
 * @property-read $files {@see GalleryPage::getFiles()}
 */
class GalleryPage extends Page {
	private $dataPath;

	public function __construct( $path )
    {
		parent::__construct( $path === '' ? $this->getSiteTitle() : self::extractName( pathinfo( $path, PATHINFO_FILENAME ) ) );
		$this->dataPath = $this->dataDirectory . $path;
	}

	public function getChildren(): array
    {
		return $this->getPages( $this->dataPath );
	}

	public function getFiles(): array
    {
		$files = [];
		foreach ( glob( $this->dataPath . '*' ) as $file ) {
			if ( is_file( $file ) ) {
				$medium = $this->createFileMetadata( $file );
				if ( null !== $medium ) {
					$files[] = $medium;
				}
			}
		}

		return $files;
	}

	private function createFileMetadata( $file ): ?array
    {
		$date    = '';
		$caption = '';

		$extension = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
		if ( in_array( $extension, [ 'jpg', 'jpeg' ] ) ) {
			$type = 'image/jpeg';
			$exif = @exif_read_data( $file, 'FILE' );
			if ( $exif ) {
				if ( isset( $exif['ImageDescription'] ) ) {
					$caption = $exif['ImageDescription'];
				}
				if ( isset( $exif['DateTimeOriginal'] ) ) {
					$date = $exif['DateTimeOriginal'];
				} elseif ( isset( $exif['FileDateTime'] ) ) {
					$date = $exif['FileDateTime'];
				}
			}
		} elseif ( in_array( $extension, [ 'png', 'gif' ] ) ) {
			$type = 'image/' . $extension;
		} elseif ( in_array( $extension, [ 'mp4', 'ogg', 'webm' ] ) ) {
			$type = 'video/' . $extension;
		} else {
			return null;
		}

		$name = pathinfo( $file, PATHINFO_FILENAME );

		$dateString = '';
		if ( strlen( $name ) >= 15 && substr( $name, 0, 15 ) && preg_match( '/[0-9]{8}_[0-9]{6}/', $name ) ) {
			$dateString = substr( $name, 0, 15 );
		}
		if ( '' === $dateString && strlen( $name ) >= 13 && substr( $name, 0, 13 ) && preg_match( '/\d{8}_\d{4}/', $name ) ) {
			$dateString = substr( $name, 0, 13 );
		}
		if ( '' === $date && $dateString !== '' ) {
			$date = date_create_from_format( ( strlen( $date ) === 15 ) ? 'Ymd_His' : $format = 'Ymd_Hi', $dateString );
		}

		if ( '' === $caption ) {
			$caption = self::extractName( substr( $name, strlen( $dateString ) ) );
		}

		return [
			'caption' => $caption,
			'date'    => $date,
			'src'     => Configuration::getDataDirectoryRelativePath() . str_replace( $this->dataDirectory, '', $file ),
			'type'    => $type,
		];
	}
}
