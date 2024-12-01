<?php

namespace Sanjayojha\PhpRestApi\Controllers;

use Sanjayojha\PhpRestApi\Core\Request;
use Sanjayojha\PhpRestApi\Enum\HTTPMethod;
use Sanjayojha\PhpRestApi\Exception\HTTPException\HTTPNotFoundException;

abstract class Controller
{
    public function handle(Request $request): never
    {
        $method = HTTPMethod::from($request->getMethod());

        switch ($method) {
            case HTTPMethod::GET:
                $this->handleGet($request);
                break;
            case HTTPMethod::POST:
                $this->handlePost($request);
                break;
            case HTTPMethod::PUT:
                $this->handlePut($request);
                break;
            case HTTPMethod::PATCH:
                $this->handlePatch($request);
                break;
            case HTTPMethod::DELETE:
                $this->handleDelete($request);
                break;
            default:
                throw new HTTPNotFoundException();
        }
    }

    protected abstract function handleGet(Request $request): never;
    protected abstract function handlePost(Request $request): never;
    protected abstract function handlePut(Request $request): never;
    protected abstract function handlePatch(Request $request): never;
    protected abstract function handleDelete(Request $request): never;
}
