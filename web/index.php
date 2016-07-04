<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Davamigo\Service\HttpProxyServer;

$proxyServer = new HttpProxyServer();

$result = $proxyServer->redirect(
    'http://www.google.com/',
    'get',
    'Mozilla/5.0',
    array(),
    array(
        CURLOPT_FOLLOWLOCATION  => true,
        CURLOPT_MAXREDIRS       => 5,
        CURLOPT_TIMEOUT         => 30
    ),
    array()
);

echo $result;
