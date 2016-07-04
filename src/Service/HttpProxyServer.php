<?php
namespace Davamigo\Service;

use Davamigo\HttpClient\CurlHttpClient\CurlHttpClient;
use Davamigo\HttpClient\Domain\HttpClient;
use Davamigo\HttpClient\Domain\HttpException;

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
     * @param string $url
     * @param string $method
     * @param string $userAgent
     * @param array  $params
     * @param array  $options
     * @param array  $headers
     * @return string|null
     * @throws HttpException
     */
    public function redirect($url, $method = 'get', $userAgent = null, array $params = array(), array $options = array(), array $headers = array())
    {
        $url .= '?' . implode('&', array_map(
            function ($param, $value) { return $param . '=' . urlencode($value); },
            array_keys($params),
            array_values($params))
        );

        $this->httpClient->setUserAgent($userAgent ?: null);

        $request = $this->httpClient->createRequest($url, $method, $headers, null, $options);

        $response = $request->send();

        return $response->getBody(true);
    }
}
