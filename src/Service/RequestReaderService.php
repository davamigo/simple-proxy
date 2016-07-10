<?php

namespace Davamigo\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestReaderService
 *
 * @package Davamigo\Service
 * @author davamigo@gmail.com
 */
class RequestReaderService
{
    /**
     * The method to use in the anonymous http proxy. Ex: GET, POST, ...
     *
     * @var string $method
     */
    protected $method = 'GET';

    /**
     * The protocol to use in the anonymous http proxy. Ex: http, https, ...
     *
     * @var string $protocol
     */
    protected $protocol = 'http';

    /**
     * The host to use in the anonymous http proxy. Ex: www.google.com
     *
     * @var string $host
     */
    protected $host = null;

    /**
     * The port to use in the anonymous http proxy. Ex: 80
     *
     * @var string $port
     */
    protected $port = null;

    /**
     * The path to use in the anonymous http proxy. Es: /search
     *
     * @var string $path
     */
    protected $path = null;

    /**
     * The query to use in the anonymous http proxy. After the question mark ?
     *
     * @var string $query
     */
    protected $query = null;

    /**
     * The hash to use in the anonymous http proxy. After the hashmark #
     *
     * @var string $hash
     */
    protected $hash = null;

    /**
     * The http body to use with post method
     *
     * @var string $body
     */
    protected $body = null;

    /**
     * The user agent to use in the anonymous http proxy. Ex: Mozilla/5.0
     *
     * @var string $agent
     */
    protected $agent = null;

    /**
     * The amount of seconds to use as timeout. Ex: 60
     *
     * @var int $timeout
     */
    protected $timeout = 60;

    /**
     * The maximum of redirection allowed. Ex: 5
     *
     * @var int $maxRedirs
     */
    protected $maxRedirs = 5;

    /**
     * The request object
     *
     * @var Request $request
     */
    protected $request;

    /**
     * RequestReaderService constructor.
     *
     * @param Request|null $request
     * @throws RequestReaderException
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request ?: Request::createFromGlobals();
        $this->extractRequestParams();
        $this->validateRequestData();
    }

    /**
     * Get the method to use in the anonymous http proxy
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the http body to use with post method
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get the user agent to use in the anonymous http proxy
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->agent;
    }

    /**
     * Get the amount of seconds to use as timeout
     *
     * @return string
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Get the maximum of redirection allowed
     *
     * @return string
     */
    public function getMaxRedirs()
    {
        return $this->maxRedirs;
    }

    /**
     * Get the full URL to use in the anonymous http proxy
     *
     * @return string
     */
    public function getUrl()
    {
        $url = $this->protocol . '://' . $this->host;

        if ($this->port) {
            $url .= ':' . $this->port;
        }

        if ($this->path) {
            $url .= $this->path;
        }

        if ($this->query) {
            $url .= '?' . $this->query;
        }

        if ($this->hash) {
            $url .= '#' . $this->hash;
        }

        return $url;
    }

    /**
     * Extracts all the params from the request object
     *
     * @return void
     */
    protected function extractRequestParams()
    {
        $url = $this->request->get('url', null);
        if ($url) {
            $components = array( // comp => var
                PHP_URL_SCHEME   => 'protocol',
                PHP_URL_HOST     => 'host',
                PHP_URL_PORT     => 'port',
                PHP_URL_PATH     => 'path',
                PHP_URL_QUERY    => 'query',
                PHP_URL_FRAGMENT => 'hash'
            );

            foreach ($components as $comp => $var) {
                $value = parse_url($url, $comp);
                $this->$var = $value ?: $this->$var;
            }
        }

        $params = array( // param => var
            'method'    => 'method',
            'protocol'  => 'protocol',
            'host'      => 'host',
            'port'      => 'port',
            'path'      => 'path',
            'query'     => 'query',
            'hash'      => 'hash',
            'body'      => 'body',
            'agent'     => 'agent',
            'timeout'   => 'timeout',
            'redirs'    => 'maxRedirs'
        );

        foreach ($params as $param => $var) {
            $this->$var = $this->request->get($param, $this->$var);
        }

        $this->method = strtoupper($this->method);
        $this->protocol = strtolower($this->protocol);
    }

    /**
     * Validates the request data
     *
     * @return void
     * @throws RequestReaderException
     */
    protected function validateRequestData()
    {
        if (!in_array($this->method, array('GET', 'POST'))) {
            throw new RequestReaderException('Method ' . $this->method . ' not allowed!');
        }

        if (!in_array($this->protocol, array('http', 'https'))) {
            throw new RequestReaderException('Protocol ' . $this->protocol. ' not allowed!');
        }

        if (empty($this->host)) {
            throw new RequestReaderException('Host required!');
        }

        if (!empty($this->port) && (!is_numeric($this->port) || $this->port < 1 || $this->port > 65535)) {
            throw new RequestReaderException('Port ' . $this->port . ' is invalid!');
        }
    }
}
