URL Shortener
=============

This is an application for shortening long urls.

REQUIREMENTS
------------

The minimum requirements by this application for your server:

* Apache web server with enabled `mod_rewrite` module
* PHP version >= 5.4.0
* PDO extension for PHP
* MySQL

INSTALLATION
------------

* Update `config/config.php` with your server database credentials
* Import database from SQL file `config/database.sql`

USAGE
-----
* Open `index.html`
* Enter an url into "Long URL" field and submit form
* Short url will appear in "Short URL" area (using `shorten.php`)
* When opening a short url the app will search in DB for url by short code and redirect to it (using `redirect.php`)
