<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use PDO;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Ramsey\Uuid\Nonstandard\Uuid;

use App\Domain\Device;
use App\Domain\Route;
use App\Domain\Station;
use App\Infrastructure\DeviceLogger;
use App\Infrastructure\DeviceRepository;
use App\Infrastructure\RouteRepository;
use App\Infrastructure\StationRepository;
use App\Infrastructure\UserRepository;

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

        $deviceRepository = new DeviceRepository($this->pdo);

        $userId = Uuid::uuid4()->toString();
        $userId2 = Uuid::uuid4()->toString();
        $device1 = new Device(Uuid::uuid4()->toString(), $userId);
        $device2 = new Device(Uuid::uuid4()->toString(), $userId);
        $device3 = new Device(Uuid::uuid4()->toString(), $userId2);

        $deviceRepository->insert($device1);
        $deviceRepository->insert($device2);
        $deviceRepository->insert($device3);

        $deviceLogger = new DeviceLogger($this->pdo);
        $logId = Uuid::uuid4()->toString();
        $deviceLogger->enter($logId, $device1, $route, $anconaStation, new \DateTimeImmutable('2022-01-01 10:00'));
        $deviceLogger->exit($logId, $bolognaStation, new \DateTimeImmutable('2022-01-01 12:00'));

        $logId = Uuid::uuid4()->toString();
        $deviceLogger->enter($logId, $device1, $route, $bolognaStation, new \DateTimeImmutable('2022-01-02 10:00'));
        $deviceLogger->exit($logId, $riminiStation, new \DateTimeImmutable('2022-01-02 11:00'));

        $logId = Uuid::uuid4()->toString();
        $deviceLogger->enter($logId, $device3, $route, $anconaStation, new \DateTimeImmutable('2022-01-02 10:00'));
        $deviceLogger->exit($logId, $riminiStation, new \DateTimeImmutable('2022-01-02 11:00'));

        $logId = Uuid::uuid4()->toString();
        $deviceLogger->enter($logId, $device2, $route, $bolognaStation, new \DateTimeImmutable('2022-02-02 10:00'));
        $deviceLogger->exit($logId, $riminiStation, new \DateTimeImmutable('2022-02-02 11:00'));

        $userRepository = new UserRepository($this->pdo);
        $monthlyDue = $userRepository->monthlyDue($userId, 1, 2022);

        $this->assertEquals(210, $monthlyDue);

        $userRepository = new UserRepository($this->pdo);
        $monthlyDue = $userRepository->monthlyDue($userId2, 1, 2022);

        $this->assertEquals(70, $monthlyDue);

        $userRepository = new UserRepository($this->pdo);
        $monthlyDue = $userRepository->monthlyDue($userId2, 2, 2022);

        $this->assertEquals(0, $monthlyDue);

        $userRepository = new UserRepository($this->pdo);
        $monthlyDue = $userRepository->monthlyDue($userId, 2, 2022);

        $this->assertEquals(70, $monthlyDue);
    }
}