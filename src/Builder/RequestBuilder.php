<?php

namespace S1njar\Igdb\Builder;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use S1njar\Igdb\Exception\BadResponseException;
use S1njar\Igdb\Response\Response;
use GuzzleHttp\Client;
use S1njar\Igdb\Builder\SearchBuilder;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequestBuilder
 * @package S1njar\Igdb
 */
class RequestBuilder
{
    /** @var \GuzzleHttp\Client */
    private $httpClient;

    /** @var \S1njar\Igdb\Response\Response */
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
     * @param \S1njar\Igdb\Builder\SearchBuilder $searchBuilder
     * @return \S1njar\Igdb\Response\Response
     * @throws \S1njar\Igdb\Exception\BadResponseException
     */
    public function build(SearchBuilder $searchBuilder): Response
    {
        $response = $this->get($searchBuilder);
        
        return $this->response->setResponse($response);
    }

    /**
     * Takes data from SearchBuilder and creates a request to igdb api.
     * If something went wrong a BadResponseException will thrown with specified exception message.
     * @param \S1njar\Igdb\Builder\SearchBuilder $searchBuilder
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \S1njar\Igdb\Exception\BadResponseException
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