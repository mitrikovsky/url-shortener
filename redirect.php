<?php

require 'config/config.php';
require 'core/UrlShortener.php';

use core\UrlShortener;

$url = '/';
if (!empty($_GET['code'])) {
    $urlShortener = new UrlShortener();

    if (!$url = $urlShortener->getUrlByCode($_GET['code'])) {
        $url = '/';
    }
}

header('Location: ' . $url);
exit;
