# Simple Media Gallery

Simple Media Gallery creates slide shows for images and videos.
Just upload your images and videos - Simple Media Gallery creates a page for each data directory.

## Documentation

### Requirements
 * [Apache](https://httpd.apache.org/) 2.4+
 * [PHP](https://secure.php.net/) 7+

### Installation
 * Download and extract the zip file.
 * Upload files (directories `public`, `src`, `vendor` and files `.htacess`, `index.php`) to your web server.
 * Create and upload a `config.php` (template see `config.sample.php`).
 * Upload media files to the `data` directory or its subdirectories.
 * Open the gallery in a browser.
 
If you run a web server other than Apache, you have to create rules equivalent to `.htacess` yourself.

### Configuration file
Options are set via `define(<option-name>, <option-value>)`: 
 * `CACHE`: set to `true` to enable caching (default value: `false`, i. e. no caching).
 * `DATA`: set the data directory (default value: `data`)
 * `SUBDIRECTORY` (optional): If Simple Media Gallery is installed in a subdirectory,
    put the path to the subdirectory here (ending with a `/`).
 * `TITLE`: the site tile
 * `COPYRIGHT`: the copyright notice in the footer.

Compare the `config.sample.php` for example values.

### Media file names
 * The names of the media files must not contain spaces or non English characters.
 * Naming them `YYYYMMDD_HHmmss-Hello-World.jpg` will automatically add a caption `Hello World` and a date `DD.MM.YYYY hh:mm` below the media files.
 * For `jpg` images the caption and date is extracted from the image metadata if set.

### Supported media formats
 * images: `jpg`, `png`
 * videos: `mp4`, `ogg` 

### Clear cache
To empty the cache, just delete the HTML files created in the `cache` directory.


## Development

### Requirements
 * [Node.js](https://nodejs.org/en/download/) 10+ and [npm](https://www.npmjs.com/) 6+
 * [Composer](https://getcomposer.org/) 1.7+
 * Apache and PHP as stated above

### Setup guide for local development environment
 * Clone the repository.
 * Install Apache and PHP, Node.js and Composer.
 * Run `npm install` to install the node dependencies.
    `npm run postinstall` is executed automatically after installation.
    This command will compile `style.scss` to `style.css`
    and copy the necessary files to the `public` directory.
 * Run `composer install` to install the composer dependencies.
 * Start a web server and point the web root to the root of the repository.
 * Open the directory in the browser. 

Now you are ready for developing.
