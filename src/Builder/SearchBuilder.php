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
    private $originalUrl;

    /** @var string */
    private $url;

    /** @var string */
    private $endpoint = 'games';

    /** @var string */
    private $body;

    /**
     * SearchBuilder constructor.
     * @param string $apiKey
     * @param string $url
     */
    public function __construct(string $apiKey, string $url = 'https://api-v3.igdb.com')
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->originalUrl = $url;
        $this->requestBuilder = new RequestBuilder();
    }

    /**
     * Builds url and searchs by instance criteria.
     * @return Response
     * @throws \Jschubert\Igdb\Exception\BadResponseException
     */
    public function search(): Response
    {
        return $this->requestBuilder->build($this);
    }

    /**
     * Builds url and searchs by id.
     * @param string $id
     * @param array $fields
     * @return Response
     * @throws \Jschubert\Igdb\Exception\BadResponseException
     */
    public function searchById(string $id, array $fields = ['*']): Response
    {
        $this->body = '';
        $this->addFields($fields);
        $this->addFilter('id', '=', $id);

        return $this->requestBuilder->build($this);
    }

    /**
     * Adds endpoint to search criteria.
     * @param string $endpoint
     * @return $this
     */
    public function addEndpoint(string $endpoint): SearchBuilder
    {
        $this->endpoint = $endpoint;
        $this->url = rtrim($this->getUrl(), '/').'/'.$this->getEndpoint();
        return $this;
    }

    /**
     * Adds search needle to search criteria.
     * @param string $search
     * @return $this
     */
    public function addSearch(string $search): SearchBuilder
    {
        $this->body .= "search \"$search\";";
        return $this;
    }

    /**
     * Adds fields to search criteria.
     * @param array $fields
     * @return $this
     */
    public function addFields(array $fields): SearchBuilder
    {
        $this->body .= 'fields ' . implode(',', $fields)  . ';';
        return $this;
    }

    /**
     * Adds filter to search criteria.
     * Note param filter can be string or array (In case of multiple values).
     * @param string $field
     * @param string $token
     * @param $filter
     * @return $this
     */
    public function addFilter(string $field, string $token, $filter): SearchBuilder
    {
        if (\is_array($filter)) {
            $filter = '(' . implode(',', $filter) . ')';
        }
        $this->body .= "where $field $token $filter;";
        return $this;
    }

    /**
     * Adds order to search criteria.
     * @param string $field
     * @param string $orderDirection
     * @return $this
     */
    public function addOrder(string $field, string $orderDirection = 'desc'): SearchBuilder
    {
        $this->body .= "sort $field $orderDirection;";
        return $this;
    }

    /**
     * Adds limit to search criteria.
     * @param string $limit
     * @return $this
     */
    public function addLimit(string $limit): SearchBuilder
    {
        $this->body .= "limit $limit;";
        return $this;
    }

    /**
     * Adds offset to search criteria.
     * @param string $offset
     * @return $this
     */
    public function addOffset(string $offset): SearchBuilder
    {
        $this->body .= "offset $offset;";
        return $this;
    }

    /**
     * Clears all search criterias.
     * @return $this
     */
    public function clear(): SearchBuilder
    {
        $this->url = $this->originalUrl;
        $this->endpoint = '';
        $this->body = '';
        return $this;
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

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}