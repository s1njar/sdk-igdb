<?php

namespace Jschubert\Igdb\Builder;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Jschubert\Igdb\Exception\BadResponseException;
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
     * Takes SearchBuilder object and returns a Response object.
     * @param \Jschubert\Igdb\Builder\SearchBuilder $searchBuilder
     * @return \Jschubert\Igdb\Response\Response
     * @throws \Jschubert\Igdb\Exception\BadResponseException
     */
    public function build(SearchBuilder $searchBuilder): Response
    {
        $response = $this->get($searchBuilder);
        
        return $this->response->setResponse($response);
    }

    /**
     * Takes data from SearchBuilder and creates a request to igdb api.
     * If something went wrong a BadResponseException will thrown with specified exception message.
     * @param \Jschubert\Igdb\Builder\SearchBuilder $searchBuilder
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Jschubert\Igdb\Exception\BadResponseException
     */
    private function get(SearchBuilder $searchBuilder): ResponseInterface
    {
        try {
            $response = $this->httpClient->post(
                $searchBuilder->getUrl(),
                [
                    'headers' => [
                        'user-key' => $searchBuilder->getApiKey()
                    ],
                    'body' => $searchBuilder->getBody()
                ]
            );
        } catch (ConnectException $connectException){
            throw  new BadResponseException($connectException->getMessage());
        } catch (RequestException $requestException){
            throw  new BadResponseException($requestException->getMessage());
        }catch (GuzzleException $guzzleException){
            throw  new BadResponseException($guzzleException->getMessage());
        }

        return $response;
    }
}