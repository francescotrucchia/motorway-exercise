<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Device;
use PDO;

final class DeviceRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insert(Device $device): void
    {
        $query = 'INSERT INTO DEVICE VALUES (:id, :userId)';

        $stm = $this->connection->prepare($query);
        $stm->execute([':id' => $device->getId(), ':userId' => $device->getUserId()]);
    }

    public function load(string $id): ?Device
    {
        $query = 'SELECT * FROM DEVICE WHERE id = :id';

        $stm = $this->connection->prepare($query);
        $stm->execute([':id' => $id]);

        $result = $stm->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new Device($result['id'], $result['user_id']);
    }
}