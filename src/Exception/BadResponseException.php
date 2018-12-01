<?php

namespace Jschubert\Igdb\Exception;

use Throwable;

class BadResponseException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}