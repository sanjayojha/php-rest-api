<?php

namespace Sanjayojha\PhpRestApi\Core;

use Sanjayojha\PhpRestApi\Exception\HTTPException\HTTPBadRequestException;
use Sanjayojha\PhpRestApi\Exception\HTTPException\HTTPNotFoundException;
use Sanjayojha\PhpRestApi\Exception\HTTPException\HTTPUnauthorizedException;

class ExceptionHandler
{
    public function __construct(protected Responder $responder) {}

    public function handle(\Throwable $e): never
    {
        $class = get_class($e);
        switch ($class) {
            case HTTPBadRequestException::class:
                $this->responder->badRequest($e->getMessage());
                break;

            case HTTPNotFoundException::class:
                $this->responder->notFound();
                break;

            case HTTPUnauthorizedException::class:
                $this->responder->unauthorized($e->getMessage());
                break;

            default:
                error_log($e);
                $this->responder->internalServerError($e->getMessage());
                break;
        }
    }
}
