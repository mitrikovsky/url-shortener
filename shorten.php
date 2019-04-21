<?php

require 'config/config.php';
require 'core/UrlShortener.php';

use core\UrlShortener;

$response = [];

if (empty($_POST['url'])) {
    $response['error'] = 'Please provide an URL';
} elseif (!filter_var($_POST['url'], FILTER_VALIDATE_URL)) {
    $response['error'] = 'URL has invalid format';
} else {
    $urlShortener = new UrlShortener();
    $code = $urlShortener->getCodeByUrl($_POST['url']);

    $response['result'] = $code;
}

echo json_encode($response);
