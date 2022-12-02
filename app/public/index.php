<?php

use App\Infrastructure\DeviceLogger;
use App\Infrastructure\DeviceRepository;
use App\Infrastructure\RouteRepository;
use App\Infrastructure\StationRepository;
use App\Infrastructure\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Slim\Factory\AppFactory;
use Webmozart\Assert\Assert;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$pdo = new PDO('sqlite:'.__DIR__.'/../data/motorway.sq3'); 

$app->get('/setup', function(Request $request, Response $response) use ($pdo){

    $pdo->exec(file_get_contents(__DIR__.'/../data/schema.sql'));
    $pdo->exec(file_get_contents(__DIR__.'/../data/fixtures.sql'));

    $response->getBody()->write('Setup done!');
    return $response;
});

$app->get('/enter/{routeId}/{stationId}/{deviceId}', function (Request $request, Response $response, $args) use ($pdo) {

    try {
        $stationRepository = new StationRepository($pdo);
        $station = $stationRepository->load($args['stationId']);

        Assert::notNull($station, 'Invalid station id');

        $routeRepository = new RouteRepository($pdo);
        $route = $routeRepository->load($args['routeId']);

        Assert::notNull($route, 'Invalid route id');

        $deviceRepository = new DeviceRepository($pdo);
        $device = $deviceRepository->load($args['deviceId']);

        Assert::notNull($device, 'Invalid device id');

        $loggerId = Uuid::uuid4()->toString();
        $deviceLogger = new DeviceLogger($pdo);
        $deviceLogger->enter($loggerId, $device, $route, $station, new \DateTimeImmutable());

        $response->getBody()->write(json_encode(['log-id' => $loggerId]));
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }

    return $response;
});

$app->get('/exit/{stationId}/{logId}', function (Request $request, Response $response, $args) use ($pdo) {

    try {
        $stationRepository = new StationRepository($pdo);
        $station = $stationRepository->load($args['stationId']);

        Assert::notNull($station, 'Invalid station id');

        $deviceLogger = new DeviceLogger($pdo);
        $loggerId = $args['logId'];
        $deviceLogger->exit($loggerId, $station, new \DateTimeImmutable());

        $response->getBody()->write(json_encode(['log-id' => $loggerId]));
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }
    
    return $response;
});

$app->get('/user/{userId}', function (Request $request, Response $response, $args) use ($pdo) {
    try {
        
        $userId = $args['userId'];
        $userRepository = new UserRepository($pdo);
        $monthlyDue = $userRepository->monthlyDue($userId, date('m'), date('Y'));

        $response->getBody()->write(json_encode(['user-id' => $userId, 'monthly-due' => $monthlyDue]));
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }
    
    return $response;
});

$app->run();
