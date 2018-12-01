<?php

namespace Tests;

use Jschubert\Igdb\Builder\RequestBuilder;
use Jschubert\Igdb\Builder\SearchBuilder;
use Jschubert\Igdb\Response\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class SearchBuilderTest
 * @package Tests
 */
class SearchBuilderTest extends TestCase
{
    /**
     * @var string
     */
    private $apiKey = 'adf89asdf9a8dsfadsf98asd';
    /**
     * @var string
     */
    private $url = 'https://api-endpoint.igdb.com';

    /** @var RequestBuilder|MockObject */
    private $requestBuilderMock;
    /** @var Response|MockObject */
    private $responseMock;
    /** @var SearchBuilder|MockObject */
    private $searchBuilderMock;

    /**
     * @test
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function search()
    {
        $endpoint = 'games';

        $parameters = [
            'filter[rating][eq]' => '75'
        ];

        $finalUrl = 'https://api-endpoint.igdb.com/games/?filter[rating][eq]=75';

        $this->searchBuilderMock
            ->expects($this->at(0))
            ->method('getUrl')
            ->willReturn($this->url);

        $this->searchBuilderMock
            ->expects($this->at(1))
            ->method('getEndpoint')
            ->willReturn($endpoint);

        $this->searchBuilderMock
            ->expects($this->at(2))
            ->method('getParameters')
            ->willReturn($parameters);

        $this->requestBuilderMock
            ->expects($this->at(0))
            ->method('build')
            ->with($this->searchBuilderMock)
            ->willReturn($this->responseMock);

        $result = $this->getSubject()->search();

        $this->assertEquals($this->responseMock, $result);
        $this->assertEquals($this->searchBuilderMock->getUrl(), $finalUrl);
    }

    /**
     * @test
     */
    public function searchByID()
    {
        $endpoint = 'games';

        $finalUrl = 'https://api-endpoint.igdb.com/games/1';

        $id = 1;

        $this->searchBuilderMock
            ->expects($this->at(0))
            ->method('getUrl')
            ->willReturn($this->url);

        $this->searchBuilderMock
            ->expects($this->at(1))
            ->method('getEndpoint')
            ->willReturn($endpoint);

        $this->requestBuilderMock
            ->expects($this->at(0))
            ->method('build')
            ->with($this->searchBuilderMock)
            ->willReturn($this->responseMock);

        $result = $this->getSubject()->searchById($id);
        $this->assertEquals($this->responseMock, $result);
        $this->assertEquals($this->searchBuilderMock->getUrl(), $finalUrl);
    }

    /**
     * @test
     */
    public function searchByScroll()
    {
        $nextPage = '/games/scroll/DXF1ZXJ5QW5kRmV0Y2gBAAAAAABZVLoWRVdqWW9YQnJUYVNBNDNYV0FWNlItUQ==/';

        $finalUrl = 'https://api-endpoint.igdb.com/games/scroll/DXF1ZXJ5QW5kRmV0Y2gBAAAAAABZVLoWRVdqWW9YQnJUYVNBNDNYV0FWNlItUQ==/';

        $this->searchBuilderMock
            ->expects($this->at(0))
            ->method('getUrl')
            ->willReturn($this->url);

        $this->requestBuilderMock
            ->expects($this->at(0))
            ->method('build')
            ->with($this->searchBuilderMock)
            ->willReturn($this->responseMock);

        $result = $this->getSubject()->searchById($nextPage);
        $this->assertEquals($this->responseMock, $result);
        $this->assertEquals($this->searchBuilderMock->getUrl(), $finalUrl);
    }

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->requestBuilderMock = $this->createMock(RequestBuilder::class);
        $this->responseMock = $this->createMock(Response::class);
        $this->searchBuilderMock = $this->createMock(SearchBuilder::class);
    }

    /**
     * @return \Jschubert\Igdb\Builder\SearchBuilder
     */
    private function getSubject(): SearchBuilder
    {
        return new SearchBuilder($this->apiKey, $this->url);
    }
}