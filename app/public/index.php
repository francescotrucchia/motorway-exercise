<?php

use App\Domain\Device;
use App\Domain\Motorway;
use App\Domain\Point;
use App\Domain\Segment;
use App\Domain\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/pass/{deviceId}/{pointId}', function (Request $request, Response $response, $args) {
    return $response;
});

$app->get('/setup', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Runway created!');

    return $response;
});

$app->run();
