<?php

namespace Sanjayojha\PhpRestApi\Exception\GatewayException;

use Throwable;

class UnknownColumnException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
