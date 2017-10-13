# Simple Media Gallery


## Documentation

### Requirements
 * [Apache](https://httpd.apache.org/)
 * [PHP](https://secure.php.net/) 7+

### Installation
 * Download and extract the zip file.
 * Upload files (directories `public`, `src`, `vendor` and files `.htacess`, `index.php`) to your webserver.
 * Create and upload a `config.php` (template see `config.sample.php`).
 * Upload media files to the `data` directory or its subdirectories.
 * Open the gallery in a browser.
 
If you run another webserver, you have to create rules equivalent to `.htacess` yourself.

### Media file names
 * The names of the media files must not contain spaces or non English characters.
 * Naming them `YYYYMMDD_HHmmss-Hello-World.jpg` will automatically add a caption `Hello World` and a date `DD.MM.YYYY hh:mm` below the media files.
 * For jpg the caption and date is extracted from the image metadata if set.

### Supported media formats
 * images: jpg, png images
 * videos: mp4 and ogg

### Configuration file
Options are set via `define(<option-name>, <option-value>)`: 
 * `CACHE`: set to `true` to enable caching (default value: `false`, i. e. no caching).
 * `DATA`: set the data directory (default value: `data`)
 * `TITLE`, `FOOTER`, `COPYRIGHT`: Add your Texts for the headline, footer text and copyright line.

Compare the `config.sample.php` for example values.

### Clear cache
To empty the cache, just delete the HTML files created in the root directory.


## Development

### Requirements
 * [Node.js](https://nodejs.org/en/download/) 6+ and [npm](https://www.npmjs.com/) 5+
 * [Composer](https://getcomposer.org/) 1.5+
 * Apache and PHP as stated above

### Setup guide for local development environment
 * Clone the repository.
 * Install Node.js, Composer and Apache.
 * Run `npm install` to install the node dependencies.
    `npm run postinstall` is executed automatically after installation.
    This command will compile `style.scss` to `style.css`
    and copy the necessary files to the public directory and also compile  
 * Run `composer install` to install the composer dependencies.
 * Start a webserver and point the webroot to the the root of the repository.
 * Open the directory in the browser. 
 * Start to develop.
