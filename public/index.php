<?php

declare(strict_types=1);

use Sanjayojha\PhpRestApi\Core\App;
use Sanjayojha\PhpRestApi\Core\ExceptionHandler;
use Sanjayojha\PhpRestApi\Core\Request;
use Sanjayojha\PhpRestApi\Core\Router;
use Sanjayojha\PhpRestApi\Enum\HTTPMethod;
use Sanjayojha\PhpRestApi\Middleware\Auth;
use Sanjayojha\PhpRestApi\Controllers\CitiesController;

const BASE_PATH = __DIR__ . "/../";

require_once BASE_PATH . "vendor/autoload.php";
require_once BASE_PATH . "src/bootstrap.php";

$exceptionHandler = App::resolve(ExceptionHandler::class);

set_exception_handler([$exceptionHandler, "handle"]);

$request = new Request();
$router = new Router();

$citiesController = App::resolve(CitiesController::class);

$router->addRoute(HTTPMethod::GET, "/v1/cities", $citiesController);
$router->addRoute(HTTPMethod::POST, "/v1/cities", $citiesController)->addMiddlewares([Auth::class]);
$router->addRoute(HTTPMethod::PUT, "/v1/cities", $citiesController)->addMiddlewares([Auth::class]);
$router->addRoute(HTTPMethod::PATCH, "/v1/cities", $citiesController)->addMiddlewares([Auth::class]);
$router->addRoute(HTTPMethod::DELETE, "/v1/cities", $citiesController)->addMiddlewares([Auth::class]);

$router->dispatch($request);
