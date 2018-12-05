<?php

namespace Jschubert\Igdb\Builder;

use Jschubert\Igdb\Builder\RequestBuilder;
use Jschubert\Igdb\Response\Response;

/**
 * Class SearchBuilder
 * @package Jschubert\Igdb
 */
class SearchBuilder
{
    /** @var RequestBuilder */
    private $requestBuilder;

    /** @var string */
    private $apiKey;

    /** @var string */
    private $url;

    /** @var string */
    private $endpoint = '';

    /** @var array */
    private $parameters = [];

    /**
     * SearchBuilder constructor.
     * @param string $apiKey
     * @param string $url
     */
    public function __construct(string $apiKey, string $url = 'https://api-endpoint.igdb.com')
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->requestBuilder = new RequestBuilder();
    }

    /**
     * @return Response
     * @throws \Jschubert\Igdb\Exception\BadResponseException
     */
    public function search(): Response
    {
        $baseUrl = rtrim($this->getUrl(), '/') . '/' . $this->getEndpoint() . '/';
        $this->url = $baseUrl . (strpos($baseUrl, '?') === false ? '?' : '') . http_build_query($this->getParameters());

        return $this->requestBuilder->build($this);
    }

    /**
     * @param string $id
     * @return Response
     * @throws \Jschubert\Igdb\Exception\BadResponseException
     */
    public function searchById(string $id): Response
    {
        $this->url = rtrim($this->getUrl(), '/').'/'.$this->getEndpoint().'/'.$id;

        return $this->requestBuilder->build($this);
    }

    /**
     * @param string $nextPage
     * @return Response
     * @throws \Jschubert\Igdb\Exception\BadResponseException
     */
    public function searchByScroll(string $nextPage): Response
    {
        $this->url = $this->getUrl() . $nextPage;

        return $this->requestBuilder->build($this);
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function addEndpoint(string $endpoint): SearchBuilder
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param string $search
     * @return $this
     */
    public function addSearch(string $search): SearchBuilder
    {
        $this->parameters['search'] = $search;
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function addFields(array $fields): SearchBuilder
    {
        $this->parameters['fields'] = implode(',', $fields);
        return $this;
    }

    /**
     * @param string $field
     * @param string $token
     * @param string $filter
     * @return $this
     */
    public function addFilter(string $field, string $token, string $filter): SearchBuilder
    {
        $this->parameters["filter[$field][$token]"] = $filter;
        return $this;
    }

    /**
     * @param string $order
     * @return $this
     */
    public function addOrder(string $order): SearchBuilder
    {
        $this->parameters['order'] = $order;
        return $this;
    }

    /**
     * @param string $limit
     * @return $this
     */
    public function addLimit(string $limit): SearchBuilder
    {
        $this->parameters['limit'] = $limit;
        return $this;
    }

    /**
     * @param string $offset
     * @return $this
     */
    public function addOffset(string $offset): SearchBuilder
    {
        $this->parameters['offset'] = $offset;
        return $this;
    }

    /**
     * @return $this
     */
    public function addScroll(): SearchBuilder
    {
        $this->parameters['scroll'] = '1';
        return $this;
    }

    /**
     * @return $this
     */
    public function clear(): SearchBuilder
    {
        $this->endpoint = '';
        $this->parameters = [];

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}