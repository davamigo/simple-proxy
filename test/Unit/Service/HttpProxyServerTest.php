<?php

namespace Test\Unit\Service;

use Davamigo\HttpClient\Domain\HttpClient;
use Davamigo\HttpClient\Domain\HttpResponse;
use Davamigo\Service\HttpProxyServer;
use Davamigo\Service\RequestReaderService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpProxyServerTest
 *
 * @package Test\Unit\Service
 * @author davamigo@gmail.com
 *
 * @group test_unit_service_httpProxyServer
 * @group test_unit_service
 * @group test_unit
 * @group test
 * @test
 * @codeCoverageIgnore
 */
class HttpProxyServerTest extends \PHPUnit_Framework_TestCase
{
    /** @var HttpClient */
    private $httpClient;

    /** @var HttpResponse */
    private $httpResponse;

    /** @var RequestReaderService */
    private $requestReaderService;

    /** @var HttpProxyServer */
    private $httpProxyServer;

    /**
     * Setup
     */
    public function setUp()
    {
        $httpClientMethods = array(
            'getClientData',
            'get',
            'head',
            'post',
            'put',
            'patch',
            'delete',
            'options',
            'send',
            'setUserAgent',
            'getDefaultUserAgent'
        );

        $this->httpClient = $this
            ->getMockBuilder('Davamigo\HttpClient\Domain\HttpClient')
            ->setMethods($httpClientMethods)
            ->getMock();

        $httpResponseMethods = array(
            'getResponseData',
            'getBody',
            'getMessage',
            'getInfo',
            'getProtocol',
            'getProtocolVersion',
            'getStatusCode',
            'getReasonPhrase',
            'getRawHeaders',
            'getHeaderLines',
            'getHeader',
            'isInformational',
            'isSuccessful',
            'isRedirect',
            'isClientError',
            'isServerError',
            'isError'
        );

        $this->httpResponse = $this
            ->getMockBuilder('Davamigo\HttpClient\Domain\HttpResponse')
            ->setMethods($httpResponseMethods)
            ->getMock();

        $requestReaderServiceMethods = array(
            'getMethod',
            'getBody',
            'getUserAgent',
            'getUrl'
        );

        $this->requestReaderService = $this
            ->getMockBuilder('Davamigo\Service\RequestReaderService')
            ->setMethods($requestReaderServiceMethods)
            ->disableOriginalConstructor()
            ->getMock();

        $this->httpProxyServer = new HttpProxyServer($this->httpClient);
    }

    /**
     * @test
     */
    public function testExecuteGetReturnsValidResponse()
    {
        $testMethod = 'GET';

        $testBody = '<html><body><p>This is a paragraph</p></body></html>';

        $testStatusCode = 200;

        $testHeaderLines = array(
            'HTTP/1.1 200 OK',
            'Content-Type: text/html; charset=UTF-8'
        );

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestReaderServiceMock */
        $requestReaderServiceMock = $this->requestReaderService;

        /** @var \PHPUnit_Framework_MockObject_MockObject $httpClientMock */
        $httpClientMock = $this->httpClient;

        /** @var \PHPUnit_Framework_MockObject_MockObject $httpResponseMock */
        $httpResponseMock = $this->httpResponse;

        $requestReaderServiceMock
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($testMethod));

        $httpClientMock
            ->expects($this->once())
            ->method('get');

        $httpClientMock
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->httpResponse));

        $httpResponseMock
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($testBody));

        $httpResponseMock
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue($testStatusCode));

        $httpResponseMock
            ->expects($this->once())
            ->method('getHeaderLines')
            ->will($this->returnValue($testHeaderLines));

        $result = $this->httpProxyServer->execute($this->requestReaderService);

        $expected = new Response($testBody, $testStatusCode, $testHeaderLines);
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function testExecuteGetWithoutContentReturnsValidResponse()
    {
        $testMethod = 'GET';

        $testBody = null;

        $testStatusCode = 404;

        $testHeaderLines = array(
            'HTTP/1.1 404 Not found'
        );

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestReaderServiceMock */
        $requestReaderServiceMock = $this->requestReaderService;

        /** @var \PHPUnit_Framework_MockObject_MockObject $httpClientMock */
        $httpClientMock = $this->httpClient;

        /** @var \PHPUnit_Framework_MockObject_MockObject $httpResponseMock */
        $httpResponseMock = $this->httpResponse;

        $requestReaderServiceMock
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($testMethod));

        $httpClientMock
            ->expects($this->once())
            ->method('get');

        $httpClientMock
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->httpResponse));

        $httpResponseMock
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($testBody));

        $httpResponseMock
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue($testStatusCode));

        $httpResponseMock
            ->expects($this->once())
            ->method('getHeaderLines')
            ->will($this->returnValue($testHeaderLines));

        $result = $this->httpProxyServer->execute($this->requestReaderService);

        $expected = new Response($testBody, $testStatusCode, $testHeaderLines);
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function testExecutePostReturnsValidResponse()
    {
        $testMethod = 'POST';

        $testBody = '<html><body><p>This is a paragraph</p></body></html>';

        $testStatusCode = 200;

        $testHeaderLines = array(
            'HTTP/1.1 200 OK',
            'Content-Type: text/html; charset=UTF-8'
        );

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestReaderServiceMock */
        $requestReaderServiceMock = $this->requestReaderService;

        /** @var \PHPUnit_Framework_MockObject_MockObject $httpClientMock */
        $httpClientMock = $this->httpClient;

        /** @var \PHPUnit_Framework_MockObject_MockObject $httpResponseMock */
        $httpResponseMock = $this->httpResponse;

        $requestReaderServiceMock
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($testMethod));

        $httpClientMock
            ->expects($this->once())
            ->method('post');

        $httpClientMock
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->httpResponse));

        $httpResponseMock
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($testBody));

        $httpResponseMock
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue($testStatusCode));

        $httpResponseMock
            ->expects($this->once())
            ->method('getHeaderLines')
            ->will($this->returnValue($testHeaderLines));

        $result = $this->httpProxyServer->execute($this->requestReaderService);

        $expected = new Response($testBody, $testStatusCode, $testHeaderLines);
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function testExecuteInvalidMethodThrowsAnException()
    {
        $testMethod = '_invalid_';

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestReaderServiceMock */
        $requestReaderServiceMock = $this->requestReaderService;

        $requestReaderServiceMock
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($testMethod));

        $this->setExpectedException('Davamigo\Service\HttpProxyException');

        $this->httpProxyServer->execute($this->requestReaderService);
    }
}
