<?php

namespace Jschubert\Igdb\Response;

use Jschubert\Igdb\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /** @var ResponseInterface */
    private $responseInterface;

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function get()
    {
        $result = json_decode($this->responseInterface->getBody());

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
     * @param ResponseInterface $responseInterface
     * @return \Jschubert\Igdb\Response\Response
     */
    public function setResponseInterface(ResponseInterface $responseInterface): Response
    {
        $this->responseInterface = $responseInterface;
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponseInterface(): ResponseInterface
    {
        return $this->responseInterface;
    }
}