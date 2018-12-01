<?php

namespace Tests;

use Jschubert\Igdb\Exception\BadResponseException;
use Jschubert\Igdb\Response\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\StreamInterface;

/**
 * Class ResponseTest
 * @package Tests
 */
class ResponseTest extends TestCase
{
    /** @var ResponseInterface|MockObject */
    private $responseInterfaceMock;
    /** @var StreamInterface|MockObject */
    private $streamInterfaceMock;

    /**
     * @test
     */
    public function getWithException()
    {
        $body = [
            'status' => '200',
            'message' => 'No such Entity'
        ];

        $exceptionMessage = 'Error 200 No such Entity';

        $this->responseInterfaceMock
            ->expects($this->at(0))
            ->method('getBody')
            ->willReturn(json_encode($body));

        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage($exceptionMessage);
    }

    /**
     * @test
     */
    public function getSingle()
    {
        $body = [
            [
                'id' => '1',
                'name' => 'witcher'
            ]
        ];

        $this->responseInterfaceMock
            ->expects($this->at(0))
            ->method('getBody')
            ->willReturn(json_encode($body));

        $result = $this->getSubject()->get();

        $this->assertEquals($body, $result[0]);
    }

    /**
     * @test
     */
    public function getMultiple()
    {
        $body = [
            [
                'id' => '1',
                'name' => 'witcher'
            ],
            [
                'id' => '2',
                'name' => 'fallout'
            ]
        ];

        $this->responseInterfaceMock
            ->expects($this->at(0))
            ->method('getBody')
            ->willReturn(json_encode($body));

        $result = $this->getSubject()->get();

        $this->assertEquals($body, $result);
    }

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->responseInterfaceMock = $this->createMock(ResponseInterface::class);
        $this->streamInterfaceMock = $this->createMock(StreamInterface::class);
    }

    /**
     * @return \Jschubert\Igdb\Response\Response
     */
    public function getSubject(): Response
    {
        return new Response();
    }
}