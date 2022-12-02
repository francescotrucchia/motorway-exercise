<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Device;
use App\Domain\Route;
use App\Domain\Station;
use PDO;

final class DeviceLogger
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function enter(string $id, Device $device, Route $route, Station $station, \DateTimeImmutable $datetime)
    {
        $query = 'INSERT INTO DEVICE_LOG(id, route_id, device_id, enter_station_id, enter_date) VALUES (:id, :route_id, :device_id, :enter_station_id, :enter_date)';

        $stm = $this->connection->prepare($query);
        $stm->execute([':id' => $id, ':route_id' => $route->getId(), ':device_id' => $device->getId(), ':enter_station_id' => $station->getId(), ':enter_date' => $datetime->format('c')]);
    }

    public function exit(string $id, Station $station, \DateTimeImmutable $datetime)
    {
        $query = 'UPDATE DEVICE_LOG SET exit_station_id = :exit_station_id, exit_date = :exit_date WHERE id = :id';

        $stm = $this->connection->prepare($query);
        $stm->execute([':id' => $id, ':exit_station_id' => $station->getId(), ':exit_date' => $datetime->format('c')]);
    }
}