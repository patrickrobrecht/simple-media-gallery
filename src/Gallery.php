<?php
namespace SimpleMediaGallery;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Gallery {
    private $copyright;
    private $dataDirectory;
    private $footer;
    private $title;

    public function __construct() {
        $this->copyright     = defined('COPYRIGHT') ? htmlspecialchars(COPYRIGHT) : '';
        $this->dataDirectory = defined('DATA') ? DATA : 'data';
        $this->footer        = defined('FOOTER') ? htmlspecialchars(FOOTER) : '';
        $this->title         = defined('TITLE') ? htmlspecialchars(TITLE) : 'Bildergalerie';
    }

    private function getFiles($directory) {
        $files = [];
        $path = $this->dataDirectory . '/' . $directory;
        foreach (glob($path . '/*') as $file) {
            if (is_file($file)) {
                $medium = self::createFileMetadata($file);
                if (null !== $medium) {
                    array_push($files, $medium);
                }
            }
        }
        return $files;
    }

    private static function createFileMetadata($file) {
        $date = '';
        $caption = '';

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg'])) {
            $type = 'image/jpg';
            $exif = exif_read_data($file, 'FILE');
            if ($exif) {
                if (isset($exif['ImageDescription'])) {
                    $caption = $exif['ImageDescription'];
                }
                if (isset($exif['DateTimeOriginal'])) {
                    $date = $exif['DateTimeOriginal'];
                } elseif (isset($exif['FileDateTime'])) {
                    $date = $exif['FileDateTime'];
                }
            }
        } elseif (in_array($extension, ['png'])) {
            $type = 'image/png';
        } elseif (in_array($extension, ['mp4'])) {
            $type = 'video/mp4';
        } elseif (in_array($extension, ['ogg'])) {
            $type = 'video/ogg';
        } else {
            return null;
        }

        $name = pathinfo($file, PATHINFO_FILENAME);

        $dateString = '';
        if (strlen($name) >= 15 && substr($name, 0, 15) && preg_match('/[0-9]{8}_[0-9]{6}/', $name)) {
            $dateString = substr($name, 0, 15);
        }
        if ('' == $dateString && strlen($name) >= 13 && substr($name, 0, 13) && preg_match('/\d{8}_\d{4}/', $name)) {
            $dateString = substr($name, 0, 13);
        }
        if ('' === $date && $dateString !== '') {
            $date = date_create_from_format((strlen($date) === 15) ? 'Ymd_His' : $format = 'Ymd_Hi', $dateString);
        }

        if ('' === $caption) {
            $caption = self::extractName(substr($name, strlen($dateString)));
        }

        return [
            'caption' => $caption,
            'date' => $date,
            'src' => $file,
            'type' => $type,
        ];
    }

    public function isDirectory($path) {
        $path = $this->dataDirectory . '/' . $path;
        return file_exists($path) && is_dir($path);
    }

    private function getMenu() {
        $menu = [];
        foreach (glob($this->dataDirectory . '/*') as $file) {
            if (is_dir($file)) {
                $name = self::extractName(pathinfo($file, PATHINFO_FILENAME));
                $item = [
                    'name' => $name,
                    'link' => str_replace('/', '', str_replace($this->dataDirectory, '', $file)) . '.html'
                ];
                array_push($menu, $item);
            }
        }
        return $menu;
    }

    private static function extractName($name) {
        // Remove digits at the beginning.
        preg_match("~^(\d+)~", $name, $m);
        if (count($m) > 0) {
            $name = substr($name, strlen($m[0]));
        }

        // Replace _ and - with spaces.
        $name = str_replace('_', ' ', $name);
        $name = str_replace('-', ' ', $name);
        return trim($name);
    }

    public function getPage($path) {
        return $this->getTwig()->render('page.html.twig',
            [
                'copyright' => $this->copyright,
                'files' => $this->getFiles($path),
                'footer' => $this->footer,
                'menu' => $this->getMenu(),
                'title' => ($path === '' ? $this->title : self::extractName(pathinfo($path, PATHINFO_FILENAME))),
            ]
        );
    }

    public function getErrorPage($text) {
        return $this->getTwig()->render('default.html.twig',
            [
                'copyright' => $this->copyright,
                'footer' => $this->footer,
                'menu' => $this->getMenu(),
                'title' => $this->title,
                'text' => $text
            ]
        );
    }

    private function getTwig() {
        return new Twig_Environment(new Twig_Loader_Filesystem('src/templates'), ['cache' => false]);
    }
}
