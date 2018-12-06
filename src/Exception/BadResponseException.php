<?php

namespace Jschubert\Igdb\Exception;

use Throwable;

/**
 * Class BadResponseException
 * @package Jschubert\Igdb\Exception
 */
class BadResponseException extends \Exception
{
    /**
     * BadResponseException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}