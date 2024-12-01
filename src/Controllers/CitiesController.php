<?php

namespace Sanjayojha\PhpRestApi\Controllers;

use Sanjayojha\PhpRestApi\Core\Request;
use Sanjayojha\PhpRestApi\Core\Responder;
use Sanjayojha\PhpRestApi\Core\Validator;
use Sanjayojha\PhpRestApi\Exception\HTTPException\HTTPNotFoundException;
use Sanjayojha\PhpRestApi\Exception\HTTPException\HTTPBadRequestException;
use Sanjayojha\PhpRestApi\Gateways\CitiesTableGateway;

class CitiesController extends Controller
{

    public function __construct(
        protected CitiesTableGateway $gateway,
        protected Responder $responder,
        protected Validator $validator
    ) {}

    protected function handleGet(Request $request): never
    {
        $params = $request->getQuery();

        if ($params["id"] ?? false) {
            $this->validateId($params["id"]);
        }
        unset($params['appid']);
        $cities = $this->gateway->findAll($params);
        if (empty($cities)) {
            throw new HTTPNotFoundException("No cities found");
        }

        $this->responder->ok(data: $cities);
    }

    protected function handlePost(Request $request): never
    {
        $data = $request->getBody();

        $this->validateFields($data, $this->gateway::ALLOWED_COLUMNS);
        $this->gateway->insert($data);
        $this->responder->created();
    }

    protected function handlePut(Request $request): never
    {
        $params = $request->getQuery();
        $data = $request->getBody();

        $this->validateId($params['id'] ?? false);
        $this->validateFields($data, $this->gateway::ALLOWED_COLUMNS);

        $this->gateway->update($params['id'], $data);
        $this->responder->ok("Resource Updated");
    }

    protected function handlePatch(Request $request): never
    {
        $params = $request->getQuery();
        $data = $request->getBody();

        $this->validateId($params['id'] ?? false);
        $this->validateFields($data, $this->gateway::ALLOWED_COLUMNS);

        $this->gateway->update($params['id'], $data);
        $this->responder->ok("Resource Updated");
    }

    protected function handleDelete(Request $request): never
    {
        $params = $request->getQuery();

        $this->validateId($params['id'] ?? false);

        $this->gateway->delete($params['id']);
        $this->responder->ok("Resource Deleted");
    }

    private function validateId(mixed $id): void
    {
        if (!$this->validator->integer($id)) {
            throw new HTTPBadRequestException("Invalid id");
        }
    }

    private function validateFields(array $data, array $allowedColumns): void
    {
        $missingColumns = $this->validator->validArray($data, $allowedColumns);
        if ($missingColumns) {
            throw new HTTPBadRequestException($missingColumns);
        }
    }
}
