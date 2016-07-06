<?php

namespace Test\Unit\Service;

use Davamigo\Service\RequestReaderService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestReaderServiceTest
 *
 * @package Test\Unit\Service
 * @author davamigo@gmail.com
 *
 * @group test_unit_service_requestReader
 * @group test_unit_service
 * @group test_unit
 * @group test
 * @test
 * @codeCoverageIgnore
 */
class RequestReaderServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();
    }

    /**
     * @test
     */
    public function testWithSimpleUrlReturnTheSameUrl()
    {
        $testUrl = 'http://test.com/';

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestMock */
        $requestMock = $this->request;
        $requestMock
            ->expects($this->exactly(10))
            ->method('get')
            ->willReturnCallback(function ($key, $default) use ($testUrl) {
                switch ($key) {
                    case 'url':
                        return $testUrl;

                    default:
                        return $default;
                }
            });

        $requestReaderService = new RequestReaderService($this->request);

        $this->assertEquals($testUrl, $requestReaderService->getUrl());
        $this->assertEquals('GET', $requestReaderService->getMethod());
        $this->assertNull($requestReaderService->getUserAgent());
        $this->assertNull($requestReaderService->getBody());
    }

    /**
     * @test
     */
    public function testWithComplexUrlReturnTheSameUrl()
    {
        $testUrl = 'http://test.com:80/place?query=vale#hash';

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestMock */
        $requestMock = $this->request;
        $requestMock
            ->expects($this->exactly(10))
            ->method('get')
            ->willReturnCallback(function ($key, $default) use ($testUrl) {
                switch ($key) {
                    case 'url':
                        return $testUrl;

                    default:
                        return $default;
                }
            });

        $requestReaderService = new RequestReaderService($this->request);

        $this->assertEquals($testUrl, $requestReaderService->getUrl());
    }

    /**
     * @test
     */
    public function testWithUrlAndMethodAndUserAgentReturnTheSameUrl()
    {
        $testUrl = 'http://test.com';
        $testMethod = 'POST';
        $testUserAgent = 'Mozilla/5.0';

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestMock */
        $requestMock = $this->request;
        $requestMock
            ->expects($this->exactly(10))
            ->method('get')
            ->willReturnCallback(function ($key, $default) use ($testUrl, $testMethod, $testUserAgent) {
                switch ($key) {
                    case 'url':
                        return $testUrl;

                    case 'method':
                        return $testMethod;

                    case 'agent':
                        return $testUserAgent;

                    default:
                        return $default;
                }
            });

        $requestReaderService = new RequestReaderService($this->request);

        $this->assertEquals($testUrl, $requestReaderService->getUrl());
        $this->assertEquals($testMethod, $requestReaderService->getMethod());
        $this->assertEquals($testUserAgent, $requestReaderService->getUserAgent());
    }

    /**
     * @test
     */
    public function testWithComponentsReturnValidUrl()
    {
        $testProtocol  = 'https';
        $testHost = 'www.test.com';
        $testPort = 8080;
        $testPath = '/resource';
        $testQuery = 'va1=val1&val2=var2';
        $testHash = 'placeholder';
        $testUrl = 'https://www.test.com:8080/resource?va1=val1&val2=var2#placeholder';

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestMock */
        $requestMock = $this->request;
        $requestMock
            ->expects($this->exactly(10))
            ->method('get')
            ->willReturnCallback(function (
                $key,
                $default
            ) use (
                $testProtocol,
                $testHost,
                $testPort,
                $testPath,
                $testQuery,
                $testHash
            ) {
                switch ($key) {
                    case 'protocol':
                        return $testProtocol;

                    case 'host':
                        return $testHost;

                    case 'port':
                        return $testPort;

                    case 'path':
                        return $testPath;

                    case 'query':
                        return $testQuery;

                    case 'hash':
                        return $testHash;

                    default:
                        return $default;
                }
            });

        $requestReaderService = new RequestReaderService($this->request);

        $this->assertEquals($testUrl, $requestReaderService->getUrl());
    }

    /**
     * @test
     */
    public function testWithIvalidMethodThrowsAnException()
    {
        $testMethod = '_invalid_';

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestMock */
        $requestMock = $this->request;
        $requestMock
            ->expects($this->exactly(10))
            ->method('get')
            ->willReturnCallback(function ($key, $default) use ($testMethod) {
                switch ($key) {
                    case 'method':
                        return $testMethod;

                    default:
                        return $default;
                }
            });

        $this->setExpectedException('Davamigo\Service\RequestReaderException');

        new RequestReaderService($this->request);
    }

    /**
     * @test
     */
    public function testWithInvalidProtocolThrowsAnException()
    {
        $testProtocol = '_invalid_';

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestMock */
        $requestMock = $this->request;
        $requestMock
            ->expects($this->exactly(10))
            ->method('get')
            ->willReturnCallback(function ($key, $default) use ($testProtocol) {
                switch ($key) {
                    case 'protocol':
                        return $testProtocol;

                    default:
                        return $default;
                }
            });

        $this->setExpectedException('Davamigo\Service\RequestReaderException');

        new RequestReaderService($this->request);
    }

    /**
     * @test
     */
    public function testWithoutHostThrowsAnException()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $requestMock */
        $requestMock = $this->request;
        $requestMock
            ->expects($this->exactly(10))
            ->method('get')
            ->willReturnCallback(function ($key, $default) {
                switch ($key) {
                    default:
                        return $default;
                }
            });

        $this->setExpectedException('Davamigo\Service\RequestReaderException');

        new RequestReaderService($this->request);
    }

    /**
     * @test
     */
    public function testWithInvaliPortThrowsAnException()
    {
        $testPort = '_invalid_';

        /** @var \PHPUnit_Framework_MockObject_MockObject $requestMock */
        $requestMock = $this->request;
        $requestMock
            ->expects($this->exactly(10))
            ->method('get')
            ->willReturnCallback(function ($key, $default) use ($testPort) {
                switch ($key) {
                    case 'host':
                        return 'www.test.com';

                    case 'port':
                        return $testPort;

                    default:
                        return $default;
                }
            });

        $this->setExpectedException('Davamigo\Service\RequestReaderException');

        new RequestReaderService($this->request);
    }
}
