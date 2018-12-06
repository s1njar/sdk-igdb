<?php

namespace Jschubert\Igdb\Response;

use Jschubert\Igdb\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /** @var ResponseInterface */
    private $response;

    /**
     * Takes body of response and return it, if there are no error code.
     * @return array|mixed
     * @throws \Exception
     */
    public function get()
    {
        $result = json_decode($this->response->getBody());

        if (isset($result->status)) {
            $message = 'Error ' . $result->status . ' ' . $result->message;
            throw new BadResponseException($message);
        }

        if (isset($result[1])) {
            return $result;
        }

        return $result[0];
    }

    /**
     * Sets response and returns Response object.
     * @param ResponseInterface $response
     * @return \Jschubert\Igdb\Response\Response
     */
    public function setResponse(ResponseInterface $response): Response
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}