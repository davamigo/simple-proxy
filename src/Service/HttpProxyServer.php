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
     * @throws HttpProxyException
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

        switch (strtoupper($method)) {
            case 'GET':
                $request = $this->httpClient->get($url, $headers, $options);
                break;

            case 'POST':
                $postBody = $request->getBody();
                $request = $this->httpClient->post($url, $headers, $postBody, $options);
                break;

            default:
                throw new HttpProxyException('Invalid method ' . $method);
        }

        $response = $this->httpClient->send($request);
        $content = $response->getBody();
        if (!$content) {
            $content = null;
        }
        $status = $response->getStatusCode();
        $headers = $response->getHeaderLines();

        return new Response($content, $status, $headers);
    }
}
