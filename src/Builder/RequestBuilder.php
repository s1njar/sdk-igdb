<?php

namespace Jschubert\Igdb\Builder;

use Jschubert\Igdb\Response\Response;
use GuzzleHttp\Client;
use Jschubert\Igdb\Builder\SearchBuilder;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequestBuilder
 * @package Jschubert\Igdb
 */
class RequestBuilder
{
    const REQUEST_METHOD = 'GET';

    /** @var \GuzzleHttp\Client */
    private $httpClient;

    /** @var \Jschubert\Igdb\Response\Response */
    private $response;

    /**
     * RequestBuilder constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client();
        $this->response = new Response();
    }

    /**
     * @param \Jschubert\Igdb\Builder\SearchBuilder $searchBuilder
     * @return \Jschubert\Igdb\Response\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function build(SearchBuilder $searchBuilder): Response
    {
        $response = $this->get($searchBuilder);
        
        return $this->response->setResponse($response);
    }

    /**
     * @param \Jschubert\Igdb\Builder\SearchBuilder $searchBuilder
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function get(SearchBuilder $searchBuilder): ResponseInterface
    {
        $response = $this->httpClient->request(
            self::REQUEST_METHOD,
            $searchBuilder->getUrl(),
            [
                'headers' => [
                    'user-key' => $searchBuilder->getApiKey(),
                    'Accept' => 'application/json'
                ]
            ]
        );

        return $response;
    }
}