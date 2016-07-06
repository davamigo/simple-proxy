<?php

namespace Davamigo\Service;

use Davamigo\HttpClient\CurlHttpClient\CurlHttpClient;
use Davamigo\HttpClient\Domain\HttpClient;
use Davamigo\HttpClient\Domain\HttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpProxyServer
 *
 * @package Davamigo\Service
 * @author davamigo@gmail.com
 */
class HttpProxyServer
{
    /** @var HttpClient */
    protected $httpClient;

    /**
     * HttpProxyServer constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new CurlHttpClient();
    }

    /**
     * Redirect to URL
     *
     * @param RequestReaderService $request
     * @param array $aditionalOptions
     * @param array $aditionalHeaders
     * @return Response
     * @throws HttpException
     */
    public function execute(
        RequestReaderService $request,
        array $aditionalOptions = array(),
        array $aditionalHeaders = array()
    ) {
        $userAgent = $request->getUserAgent();
        $this->httpClient->setUserAgent($userAgent ?: null);

        $url = $request->getUrl();
        $method = $request->getMethod();
        $options = $aditionalOptions + array(
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 5,
            CURLOPT_TIMEOUT         => 30
        );
        $headers = $aditionalHeaders;

        $body = null;
        if ($method == 'POST') {
            $body = $request->getBody();
        }

        $request = $this->httpClient->createRequest($url, $method, $headers, $body, $options);
        $response = $this->httpClient->send($request);

        return new Response($response->getBody(), $response->getStatusCode(), $response->getHeaderLines());
    }
}
