<?php

namespace Sanjayojha\PhpRestApi\Exception\HTTPException;

use Throwable;

class HTTPException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
