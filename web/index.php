<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Davamigo\Service\HttpProxyServer;
use Davamigo\Service\RequestReaderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

try {
    $requestReader = new RequestReaderService($request);
    $proxyServer = new HttpProxyServer();
    $response = $proxyServer->redirect($requestReader);

} catch (\Exception $exc) {
    $msg = $exc->getMessage();
    if ($exc->getCode()) {
        $msg = $exc->getCode() . ' ' . $msg;
    }

    $html = '<html>';
    $html .= '<head>';
    $html .= '<title>';
    $html .= $msg;
    $html .= '</title>';
    $html .= '</head>';
    $html .= '<body>';
    $html .= '<h1>Error: ' . $msg . '</h1>';
    $html .= '<h2>Anonymous HTTP Proxy params:</h2>';
    $html .= '<ul>';
    $html .= '<li><strong>method</strong>: The method to use. Ex: GET, POST</li>';
    $html .= '<li><strong>agent</strong>: The user agent to use. Ex: Mozilla/5.0';
    $html .= '<li><strong>url</strong>: The full url to use. Ex: http://www.google.es/';
    $html .= '</ul>';
    $html .= '<h3>More params:</h3>';
    $html .= '<ul>';
    $html .= '<li><strong>protocol</strong>: The protocol to use. Ex: http, https, ...';
    $html .= '<li><strong>host</strong>: The host to use. Ex: www.google.com';
    $html .= '<li><strong>port</strong>: The port to use. Ex: 80';
    $html .= '<li><strong>path</strong>: The path to use. Es: /search';
    $html .= '<li><strong>query</strong>: The query to use. After the question mark ?';
    $html .= '<li><strong>hash</strong>: The hash to use. After the hashmark #';
    $html .= '<li><strong>body</strong>: The http body to use with post method';
    $html .= '</ul>';
    $html .= '<h3>Example:</h3>';
    $html .= '<pre>';
    $html .= $request->getUriForPath('?method=get&url=https://www.amazon.es/gp/product/B00YNM5HCC');
    $html .= '</pre>';
    $html .= '</body>';
    $html .= '</html>';

    $response = new Response(
        $html,
        Response::HTTP_INTERNAL_SERVER_ERROR,
        array('Content-type' => 'text/html; charset=UTF-8')
    );
}

$response->send();
