<?php

namespace Tests;

use GuzzleHttp\Client;
use Jschubert\Igdb\Builder\RequestBuilder;
use Jschubert\Igdb\Builder\SearchBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequestBuilderTest
 * @package Tests
 */
class RequestBuilderTest extends TestCase
{
    /** @var RequestBuilder|MockObject */
    private $requestBuilderMock;
    /** @var Client|MockObject */
    private $clientMock;
    /** @var SearchBuilderTest|MockObject */
    private $searchBuilderMock;
    /** @var ResponseInterface|MockObject */
    private $responseInterface;

    /**
     * @test
     */
    public function build()
    {
        $url = 'https://api-endpoint.igdb.com/games/?filter[rating][eq]=75';
        $apiKey = '8293596464461cd84edf92dec0363a1e';

        $this->searchBuilderMock
            ->expects($this->at(0))
            ->method('getUrl')
            ->willReturn($url);

        $this->searchBuilderMock
            ->expects($this->at(1))
            ->method('getApiKey')
            ->willReturn($apiKey);

        $this->clientMock
            ->expects($this->at(0))
            ->method('request')
            ->with(
                'GET',
                $url,
                [
                    'headers' => [
                        'user-key' => $apiKey,
                        'Accept' => 'application/json'
                    ]
                ]
            )
            ->willReturn($this->responseInterface);

        $result = $this->getSubject()->build($this->searchBuilderMock);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->clientMock = $this->createMock(Client::class);
        $this->requestBuilderMock = $this->createMock(RequestBuilder::class);
        $this->searchBuilderMock = $this->createMock(SearchBuilder::class);
        $this->responseInterface = $this->createMock(ResponseInterface::class);
    }

    /**
     * @return \Jschubert\Igdb\Builder\RequestBuilder
     */
    public function getSubject(): RequestBuilder
    {
        return new RequestBuilder();
    }
}