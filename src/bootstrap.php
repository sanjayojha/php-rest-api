<?php

use Sanjayojha\PhpRestApi\Core\App;
use Sanjayojha\PhpRestApi\Core\Container;
use Sanjayojha\PhpRestApi\Core\Database;
use Sanjayojha\PhpRestApi\Core\ExceptionHandler;
use Sanjayojha\PhpRestApi\Core\Responder;
use Sanjayojha\PhpRestApi\Core\Request;
use Sanjayojha\PhpRestApi\Core\Validator;
use Sanjayojha\PhpRestApi\Middleware\Auth;
use Sanjayojha\PhpRestApi\Controllers\CitiesController;
use Sanjayojha\PhpRestApi\Gateways\CitiesTableGateway;

$confg = require_once BASE_PATH . "src/config.php";

$container = new Container();

$container->set(Database::class, fn() => new Database($confg["database"]));
$container->set(ExceptionHandler::class, fn() => new ExceptionHandler(new Responder)); //TODO: add more in ExceptionHandler
$container->set(Auth::class, fn() => new Auth($container->get(Database::class), new Request));
$container->set(CitiesTableGateway::class, fn() => new CitiesTableGateway($container->get(Database::class)));
$container->set(CitiesController::class, fn() => new CitiesController($container->get(CitiesTableGateway::class), new Responder, new Validator));

App::setContainer($container);
