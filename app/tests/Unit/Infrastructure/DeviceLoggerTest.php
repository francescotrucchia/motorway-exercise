<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure;

use App\Domain\Device;
use App\Domain\Route;
use App\Domain\Station;
use App\Infrastructure\DeviceLogger;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\Rule\AnyParameters;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Ramsey\Uuid\Nonstandard\Uuid;

class DeviceLoggerTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_enters()
    {
        $id = Uuid::uuid4()->toString();
        $routeId = Uuid::uuid4()->toString();
        $deviceId = Uuid::uuid4()->toString();
        $stationId = Uuid::uuid4()->toString();
        $datetime = new \DateTimeImmutable();

        $device = $this->prophesize(Device::class);
        $device->getId()->willReturn($deviceId);
        $route = $this->prophesize(Route::class);
        $route->getId()->willReturn($routeId);
        $station = $this->prophesize(Station::class);
        $station->getId()->willReturn($stationId);

        $stm = $this->prophesize(PDOStatement::class);
        $stm->execute([
            ':id' => $id,
            ':route_id' => $routeId, 
            ':device_id' => $deviceId, 
            ':enter_station_id' => $stationId, 
            ':enter_date' => $datetime->format('c')
        ])->willReturn(true)->shouldBeCalled();

        $expectedQuery = 'INSERT INTO DEVICE_LOG(id, route_id, device_id, enter_station_id, enter_date) VALUES (:id, :route_id, :device_id, :enter_station_id, :enter_date)';
        $connection = $this->prophesize(PDO::class);
        $connection->prepare($expectedQuery)->willReturn($stm->reveal())->shouldBeCalled();

        $deviceLogger = new DeviceLogger($connection->reveal());
        $deviceLogger->enter($id, $device->reveal(), $route->reveal(), $station->reveal(), $datetime);
    }

    /**
     * @test
     */
    public function it_exits()
    {
        $id = Uuid::uuid4()->toString();
        $stationId = Uuid::uuid4()->toString();
        $datetime = new \DateTimeImmutable();

        $station = $this->prophesize(Station::class);
        $station->getId()->willReturn($stationId);

        $stm = $this->prophesize(PDOStatement::class);
        $stm->execute([
            ':id' => $id,
            ':exit_station_id' => $stationId, 
            ':exit_date' => $datetime->format('c')
        ])->willReturn(true)->shouldBeCalled();

        $expectedQuery = 'UPDATE DEVICE_LOG SET exit_station_id = :exit_station_id, exit_date = :exit_date WHERE id = :id';
        $connection = $this->prophesize(PDO::class);
        $connection->prepare($expectedQuery)->willReturn($stm->reveal())->shouldBeCalled();

        $deviceLogger = new DeviceLogger($connection->reveal());
        $deviceLogger->exit($id, $station->reveal(), $datetime);
    }
}