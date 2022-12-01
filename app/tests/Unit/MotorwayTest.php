<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Device;
use App\Domain\Route;
use App\Domain\Station;
use App\Infrastructure\DeviceRepository;
use App\Infrastructure\RouteRepository;
use App\Infrastructure\StationRepository;
use PDO;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Ramsey\Uuid\Nonstandard\Uuid;

final class MotorwayTest extends TestCase
{
    private $pdo;

    use ProphecyTrait;

    public function setUp(): void
    {
        $pdo = new PDO(
            'sqlite::memory:',
            null,
            null,
            array(PDO::ATTR_PERSISTENT => true)
        ); 

        $pdo->exec(file_get_contents(__DIR__.'/../../data/schema.sql'));

        $this->pdo = $pdo;
    }

    /**
     * @test
     */
    public function it_create_a_motorway()
    {
        $stationRepository = new StationRepository($this->pdo);
        $routeRepository = new RouteRepository($this->pdo);

        $anconaStationId = Uuid::uuid4()->toString();
        $anconaStation = new Station($anconaStationId, 'Ancona');
        $riminiStation = new Station(Uuid::uuid4()->toString(), 'Rimini');
        $bolognaStation = new Station(Uuid::uuid4()->toString(), 'Bologna');

        $stationRepository->insert($anconaStation);
        $stationRepository->insert($riminiStation);
        $stationRepository->insert($bolognaStation);

        $routeId = Uuid::uuid4()->toString();
        $route = new Route($routeId, 'A14', 0.7);
        $route->addStation($anconaStation, 0);
        $route->addStation($riminiStation, 100);
        $route->addStation($bolognaStation, 200);
        
        $routeRepository->insert($route);

//        $stm = $this->pdo->prepare('Select * from ROUTE_STATION');
//        $stm->execute();
//        $results = $stm->fetchAll(PDO::FETCH_ASSOC);

        $deviceRepository = new DeviceRepository($this->pdo);

        $userId = Uuid::uuid4()->toString();
        $device1 = new Device(Uuid::uuid4()->toString(), $userId);
        $device2 = new Device(Uuid::uuid4()->toString(), $userId);

        $deviceRepository->insert($device1);
        $deviceRepository->insert($device2);


        // $device1->enter(Uuid::uuid4()->toString(), $route->getId(), $anconaStation->getId(), new \DateTimeImmutable('2022-01-01 10:00'));
        // $device1->exit(Uuid::uuid4()->toString(), $route->getId(), $riminiStation->getId(), new \DateTimeImmutable('2022-01-01 12:00'));

        
    }
}