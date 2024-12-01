<?php

namespace Sanjayojha\PhpRestApi\Exception\HTTPException;

use Throwable;

class HTTPUnauthorizedException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
